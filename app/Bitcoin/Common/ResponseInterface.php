<?php

namespace App\Bitcoin\Common;

interface ResponseInterface
{
    public function getPrice(): float;
}
