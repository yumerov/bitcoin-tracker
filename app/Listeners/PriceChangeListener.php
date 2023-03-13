<?php

namespace App\Listeners;

use App\Events\PriceChangeEvent;
use App\Services\PriceChangeNotificationService;

class PriceChangeListener
{
    public function __construct(
        private readonly PriceChangeNotificationService $service
    )
    {
    }

    public function handle(PriceChangeEvent $event): void
    {
        $this->service->notifySubscribers($event->price);
    }
}
