<?php

namespace App\Event\Subscriber;

use App\Event\ContactCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ContactSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ContactCreated::class => [
                ['sendEmail', 10],
                ['sendSms', 5],
            ],
        ];
    }

    public function sendEmail(ContactCreated $event): void
    {
        // ...
    }
    
    public function sendNotification(ContactCreated $event)
    {
        // ...
    }
}