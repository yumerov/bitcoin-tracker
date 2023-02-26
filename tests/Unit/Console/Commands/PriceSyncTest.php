<?php

namespace Unit\Console\Commands;

use App\Console\Commands\PriceSync;
use App\Services\PriceSynchronizer;
use Illuminate\Console\OutputStyle;
use PHPUnit\Framework\TestCase;

class PriceSyncTest extends TestCase
{

    public function testHandle()
    {
        // Arrange
        $priceSynchronizer = $this->createMock(PriceSynchronizer::class);
        $output = $this->createMock(OutputStyle::class);
        $command = new PriceSync($priceSynchronizer);
        $command->setOutput($output);

        $priceSynchronizer
            ->expects($this->once())
            ->method('synchronize');

        $output
            ->expects($this->exactly(2))
            ->method('writeln');

        // Act
        $command->handle();

        // Assert
    }
}
