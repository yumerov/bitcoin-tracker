<?php

namespace App\Bitcoin\Bitfinex;

use App\Bitcoin\Common\ResponseInterface;

class ResponseAdapter implements ResponseInterface
{
    public function __construct(
        private readonly Response $response
    ) { }

    public function getPrice(): float
    {
        return $this->response->lastPrice;
    }
}
