<?php

namespace Unit\Listeners;

use App\Events\PriceChangeEvent;
use App\Listeners\PriceChangeListener;
use App\Services\PriceChangeNotificationService;
use PHPUnit\Framework\MockObject\Exception as MockException;
use PHPUnit\Framework\TestCase;

class PriceChangeListenerTest extends TestCase
{

    /**
     * @return void
     * @throws MockException
     */
    public function test(): void
    {
        // Arrange
        $price = 68564;
        $service = $this->createMock(PriceChangeNotificationService::class);
        $listener = new PriceChangeListener($service);
        $event = new PriceChangeEvent($price);
        $service->expects($this->once())
            ->method('notifySubscribers')
            ->with($event->price);

        // Act
        $listener->handle($event);
    }
}
