<?php

namespace App\Bitcoin\Bitfinex;

class Response
{
    public function __construct(
        public ?float $mid,
        public ?float $bid,
        public ?float $ask,
        public float $lastPrice,
        public ?float $low,
        public ?float $high,
        public ?float $volume,
        public float $timestamp,
    ) { }
}
