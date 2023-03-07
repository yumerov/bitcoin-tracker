<?php

namespace Unit\Services;

use App\DTO\PriceNotificationDTO;
use App\Models\PriceNotification;
use App\Services\PriceNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\Exception as MockException;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class PriceNotificationServiceTest extends TestCase
{

    use RefreshDatabase;
    private PriceNotificationDTO $dto;
    private LoggerInterface $logger;
    private PriceNotificationService $service;

    /**
     * @return void
     * @throws MockException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshInMemoryDatabase();

        $this->dto = new PriceNotificationDTO('email@example.com', 69000);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->service = new PriceNotificationService($this->logger);
    }

    public function test_subscribe_successfully()
    {
        // Arrange
        $this->logger
            ->expects($this->never())
            ->method('error');

        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        // Act
        $result = $this->service->subscribe($this->dto);

        // Assert
        $this->assertTrue($result);
    }

    public function test_subscribe_failed()
    {
        // Arrange
        (new PriceNotification([
            'email' => $this->dto->email,
            'price' => $this->dto->price
        ]))->save();
        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with("Subscribing email '{$this->dto->email}' for price '{$this->dto->price}'");

        // Act
        $result = $this->service->subscribe($this->dto);

        // Assert
        $this->assertFalse($result);
    }
}
