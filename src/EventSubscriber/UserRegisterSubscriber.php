<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Email\Mailer;
use App\Entity\User;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegisterSubscriber implements EventSubscriberInterface
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * @var TokenGenerator
	 */
	private $tokenGenerator;
	/**
	 * @var Mailer
	 */
	private $mailer;


	public function __construct(
		UserPasswordEncoderInterface $password_encoder,
		TokenGenerator $token_generator,
		Mailer $mailer
	)
	{
		$this->passwordEncoder = $password_encoder;
		$this->tokenGenerator = $token_generator;
		$this->mailer = $mailer;
	}

	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::VIEW => ['UserRegistered', EventPriorities::PRE_WRITE]
		];
	}

	public function UserRegistered(GetResponseForControllerResultEvent $event)
	{
		$user = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();

		if ( !$user instanceof User || !in_array($method, [Request::METHOD_POST]))
		{
			return;
		}

		/**
		 * @var User $user
		 */
		$user->setPassword(
			$this->passwordEncoder->encodePassword($user, $user->getPassword())
		);

		$user->setConfirmationToken(
			$this->tokenGenerator->getRandomSecureToken()
		);

		$this->mailer->sendConfirmationMail($user);
	}
}