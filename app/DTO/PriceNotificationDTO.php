<?php

namespace App\DTO;

class PriceNotificationDTO
{
    public function __construct(
        public readonly string $email,
        public readonly float $price
    ) { }
}
