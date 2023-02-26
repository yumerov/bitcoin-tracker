<?php

namespace Unit\Bitcoin\Bitfinex;

use App\Bitcoin\Bitfinex\Response;
use App\Bitcoin\Bitfinex\ResponseAdapter;
use PHPUnit\Framework\TestCase;

class ResponseAdapterTest extends TestCase
{

    public function testGetPrice()
    {
        // Arrange
        $response = new Response(null,
            null,
            null,
            69000,
            null,
            null,
            null,
            1677407246);

        // Act
        $adaptedResponse = new ResponseAdapter($response);

        // Assert
        $this->assertEquals($response->lastPrice, $adaptedResponse->getPrice());
    }
}
