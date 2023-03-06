<?php

namespace Unit\Console;

use App\Console\Kernel;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Events\Dispatchable;
use Tests\TestCase;

class KernelTest extends TestCase
{

    public function test_schedule()
    {
        // Arrange && Assert
        $command = 'price:sync';
        $schedule = $this->createMock(Schedule::class);
        $schedule->expects($this->once())
            ->method('command')
            ->willReturn(new Event($this->createEventMutex(), $command));

        // Act
        $this->createConsoleKernel()->schedule($schedule);
    }

    private function createConsoleKernel()
    {
        return new class(
            $this->createMock(Application::class), $this->createMock(Dispatcher::class)) extends Kernel {
            public function schedule(Schedule $schedule): void
            {
                parent::schedule($schedule);
            }
        };
    }

    private function createEventMutex(): EventMutex
    {
        return new class implements EventMutex
        {
            public function create(Event $event) { }
            public function exists(Event $event) { }
            public function forget(Event $event) { }
        };
    }
}
