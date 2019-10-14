<?php

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Interfaces\PublishedDateEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PublishedDateEntitySubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDatePublished', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setDatePublished(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if (!$entity instanceof PublishedDateEntityInterface || Request::METHOD_POST !== $method) {
            return;
        }

        $entity->setPublished(new \DateTime());
    }

}