<?php

namespace Feature\Endpoints;

use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->migrateUsing();
        $this->refreshInMemoryDatabase();
    }

    public function test(): void
    {
        // Arrange
        $prices = [];

        // Act
        $response = $this->get('/api/prices');

        // Assert
        $response->assertStatus(200);
        $response->assertContent('[]');

        // Arrange
        $prices[] = (new Price([
            'price' => 20000,
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
        ]));
        $prices[0]->save();

        // Act
        $response = $this->get('/api/prices');

        // Assert
        $response->assertStatus(200);
        $response->assertContent("{\"{$prices[0]->timestamp}\":{$prices[0]->price}}");

        // Arrange
        $prices[] = (new Price([
            'price' => 25300,
            'timestamp' => Carbon::now()->addMinute()->format('Y-m-d H:i:s')
        ]));
        $prices[1]->save();

        // Act
        $response = $this->get('/api/prices');

        // Assert
        $response->assertStatus(200);
        $response->assertContent("{\"{$prices[0]->timestamp}\":{$prices[0]->price},\"{$prices[1]->timestamp}\":{$prices[1]->price}}");
    }
}
