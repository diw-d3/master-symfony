<?php

namespace App\Listener;

use App\Event\ProductCreated;

class SendEmailListener
{
    public function onProductCreated(ProductCreated $event)
    {
        dump($event->getProduct());
    }
}
