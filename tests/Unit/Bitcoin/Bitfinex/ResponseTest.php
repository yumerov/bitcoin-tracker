<?php

namespace Unit\Bitcoin\Bitfinex;

use App\Bitcoin\Bitfinex\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    /**
     * @param float|null $mid
     * @param float|null $bid
     * @param float|null $ask
     * @param float $lastPrice
     * @param float|null $low
     * @param float|null $high
     * @param float|null $volume
     * @param float $timestamp
     * @dataProvider dataProvider
     */
    public function testConstructor(
        ?float $mid,
        ?float $bid,
        ?float $ask,
        float $lastPrice,
        ?float $low,
        ?float $high,
        ?float $volume,
        float $timestamp
    )
    {
        // Act
        $response = new Response($mid, $bid, $ask, $lastPrice, $low, $high,$volume, $timestamp);

        // Assert
        $this->assertEquals($mid, $response->mid);
        $this->assertEquals($bid, $response->bid);
        $this->assertEquals($ask, $response->ask);
        $this->assertEquals($lastPrice, $response->lastPrice);
        $this->assertEquals($low, $response->low);
        $this->assertEquals($high, $response->high);
        $this->assertEquals($volume, $response->volume);
        $this->assertEquals($timestamp, $response->timestamp);
    }

    public static function dataProvider(): array
    {
        return [
            [
                null,
                null,
                null,
                69000,
                null,
                null,
                null,
                1677407246
            ],
            [
                23266.5,
                23266.0,
                23267.0,
                23266.0,
                22810.0,
                23302.0,
                980.72109237,
                1677407589.383896
            ]
        ];
    }
}
