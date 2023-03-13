<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->assertDatabaseHas('price_subscriptions', $data);
    }

    public function test_subscribe_second_time_with_same_data(): void
    {
        // Arrange
        $data = [
            'email' => self::EMAIL,
            'price' => self::PRICE
        ];
        $this->post('/api/subscribe', $data);
        $this->assertDatabaseCount('price_subscriptions', 1);

        // Act
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(422);
        $response->assertContent("{\"errors\":{\"price\":[\"Already the email '" . self::EMAIL . "' for the price '" . self::PRICE . "'\"]}}");
        $this->assertDatabaseCount('price_subscriptions', 1);
    }

    public function test_subscribe_price_precision(): void
    {
        // Arrange
        $data = [
            'email' => self::EMAIL,
            'price' => self::PRICE
        ];
        $this->post('/api/subscribe', $data);
        $this->assertDatabaseCount('price_subscriptions', 1);
        $data['price'] += 0.001;

        // Act
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(422);
        $response->assertContent("{\"errors\":{\"price\":[\"Already the email '" . self::EMAIL . "' for the price '" . $data['price'] . "'\"]}}");
        $this->assertDatabaseCount('price_subscriptions', 1);
    }

    public function test_subscribe_fail_data_persist(): void
    {
        $this->markTestIncomplete('Currently cannot cause data persist issue to return an error message');
    }

    public function test_subscribe_email_validations(): void
    {
        // Arrange
        $data = [
            'price' => self::PRICE
        ];

        // Act
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(422);
        $response->assertContent('{"errors":{"email":["The email field is required."]}}');
        $this->assertDatabaseCount('price_subscriptions', 0);

        // Act
        $data['email'] = 'wrongemailformat';
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(422);
        $response->assertContent('{"errors":{"email":["The email field must be a valid email address."]}}');
        $this->assertDatabaseCount('price_subscriptions', 0);
    }

    public function test_subscribe_price_validations(): void
    {
        // Arrange
        $data = [
            'email' => self::EMAIL
        ];

        // Act
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(422);
        $response->assertContent('{"errors":{"price":["The price field is required."]}}');
        $this->assertDatabaseCount('price_subscriptions', 0);

        // Act
        $data['price'] = -1;
        $response = $this->post('/api/subscribe', $data);

        // Assert
        $response->assertStatus(422);
        $response->assertContent('{"errors":{"price":["The price field must be greater than 0."]}}');
        $this->assertDatabaseCount('price_subscriptions', 0);
    }
}
