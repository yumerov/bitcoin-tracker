<?php

namespace Tests\Feature;

use App\Models\PriceNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    private const EMAIL = 'user@gmail.com';
    private const PRICE = 69000;

    protected function setUp(): void
    {
        parent::setUp();
        $this->migrateUsing();
        $this->refreshInMemoryDatabase();
    }

    public function test_subscribe_successfully(): void
    {
        // Arrange
        $data = [
            'email' => self::EMAIL,
            'price' => self::PRICE
        ];

        // Act
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(204);
        $this->assertDatabaseHas('price_notifications', $data);
    }

    public function test_subscribe_second_time_with_same_data(): void
    {
        // Arrange
        $data = [
            'email' => self::EMAIL,
            'price' => self::PRICE
        ];
        $response = $this->post('/api/subscribe', $data);
        $this->assertDatabaseCount('price_notifications', 1);

        // Act
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(422);
        $this->assertEquals("{\"errors\":{\"price\":[\"Already the email '" . self::EMAIL . "' for the price '" . self::PRICE . "'\"]}}", $response->getContent());
        $this->assertDatabaseCount('price_notifications', 1);
    }

    public function test_subscribe_fail_data_persist(): void
    {
        $this->markTestIncomplete('Currently cannot cause data persist issue to return an error message');
    }
}
