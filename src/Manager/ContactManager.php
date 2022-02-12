<?php

namespace App\Manager;

use App\Entity\Contact;
use App\Event\ContactCreated;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ContactManager 
{
    private EventDispatcherInterface $eventDispatcher;
    
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function add(Contact $contact): void
    {
        $this->eventDispatcher->dispatch(new ContactCreated($contact));
    }
}