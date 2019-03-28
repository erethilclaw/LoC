<?php

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\CreatedDateEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CreatedDateEntitySubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents() {
		return [
			KernelEvents::VIEW=>['setCreatedDate', EventPriorities::PRE_WRITE]
		];
	}

	public function setCreatedDate(GetResponseForControllerResultEvent $event)
	{
		$entity = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();

		if (!$entity instanceof CreatedDateEntityInterface  || Request::METHOD_POST !== $method){
			return;
		}

		$entity->setCreated(new \DateTime());
	}
}