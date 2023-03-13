<?php

namespace Unit\Services;

use App\Models\PriceSubscription;
use App\Services\PriceChangeNotificationService;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\PendingMail;
use PHPUnit\Framework\MockObject\Exception as MockException;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class PriceChangeNotificationServiceTest extends TestCase
{

    use RefreshDatabase;

    private const PRICE = 67675;
    private const EMAIL = 'test@mail.com';

    private PriceChangeNotificationService $service;

    /**
     * @return void
     * @throws MockException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrateUsing();
        $this->refreshInMemoryDatabase();

        $logger = $this->createMock(LoggerInterface::class);
        $mailer = $this->createMock(Mailer::class);
        $this->service = new PriceChangeNotificationService($logger,$mailer);

        $mailer
            ->method('to')
            ->willReturn($this->createPendingMail());
    }

    public function test_notify_subscribers(): void
    {
        // Arrange
        $data = [
            'price' => self::PRICE - 1,
            'email' => self::EMAIL,
            'active' => true
        ];
        (new PriceSubscription($data))->save();

        // Act
        $this->service->notifySubscribers(self::PRICE);

        // Assert
        $data['active'] = false;
        $data['deactivated_at'] = self::PRICE;
        $this->assertDatabaseHas('price_subscriptions', $data);
    }

    /**
     * @test The test begins with deactivated description and getting lower one should re-activate it
     * @return void
     */
    public function test_reactive_deactivated_subscriptions(): void
    {
        // Arrange
        $data = [
            'price' => self::PRICE - 10,
            'deactivated_at' => self::PRICE,
            'email' => self::EMAIL,
            'active' => false
        ];
        (new PriceSubscription($data))->save();

        // Act
        $this->service->notifySubscribers($data['price'] - 1);

        // Assert
        $data['deactivated_at'] = null;
        $data['active'] = true;
        $this->assertDatabaseHas('price_subscriptions', $data);
    }

    /**
     * @return PendingMail
     * @throws MockException
     */
    private function createPendingMail(): PendingMail
    {
        return $this->createMock(PendingMail::class);
    }
}
