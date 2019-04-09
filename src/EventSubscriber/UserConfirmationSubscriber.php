<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\UserConfirmation;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriber implements EventSubscriberInterface
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	public function __construct(
		UserRepository $userRepository,
		EntityManagerInterface $em
	) {
		$this->userRepository = $userRepository;
		$this->em = $em;
	}

	public static function getSubscribedEvents() {
		return [
			KernelEvents::VIEW => ['confirmUser', EventPriorities::PRE_VALIDATE]
		];
	}

	public function confirmUser(GetResponseForControllerResultEvent $event)
	{
		$request = $event->getRequest();

		if ('api_user_confirmations_post_collection' !== $request->get('_route')) {
			return;
		}

		/**
		 * @var UserConfirmation $confirmationToken
		 */
		$confirmationToken = $event->getControllerResult();

		$user = $this->userRepository->findOneBy([
			'confirmationToken'=> $confirmationToken->confirmationToken
		]);

		if (!$user){
			throw new NotFoundHttpException();
		}

		$user->setEnabled(true);
		$user->setConfirmationToken(null);

		$this->em->flush();

		$event->setResponse(
			new JsonResponse(null, Response::HTTP_OK)
		);
	}
}