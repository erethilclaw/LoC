<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PaswordHashSubscriber implements EventSubscriberInterface
{
	public function __construct(UserPasswordEncoderInterface $password_encoder)
	{
		$this->passwordEncoder = $password_encoder;
	}

	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE]
		];
	}

	public function hashPassword(GetResponseForControllerResultEvent $event)
	{
		$user = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();

		if ( !$user instanceof User || !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT]))
		{
			return;
		}

		/**
		 * @var User $user
		 */
		$user->setPassword(
			$this->passwordEncoder->encodePassword($user, $user->getPassword())
		);
	}
}