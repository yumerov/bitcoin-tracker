<?php

namespace Unit\Rules;

use App\Models\PriceNotification;
use App\Rules\UniquePriceNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniquePriceNotificationTest extends TestCase
{

    use RefreshDatabase;

    private UniquePriceNotification $rule;
    private const EMAIL = "user@example.com";
    private const PRICE = 69000;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshInMemoryDatabase();
        $this->rule = new UniquePriceNotification();
    }

    public function test_validate_not_existing()
    {
        // Arrange
        $valid = true;

        // Act
        $this->rule->validate(
            'attribute',
            [self::EMAIL, self::PRICE],
            function () use (&$valid) {
                $valid = false;
            });

        // Assert
        $this->assertTrue($valid);
    }

    public function test_validate_existing()
    {
        // Arrange
        $valid = false;
        (new PriceNotification([
            'email' => self::EMAIL,
            'price' => self::PRICE
        ]))->save();

        // Act
        $this->rule->validate(
            'attribute',
            [self::EMAIL, self::PRICE],
            function () use (&$valid) {
                $valid = true;
            });

        // Assert
        $this->assertTrue($valid);
    }
}
