<?php

namespace App\Bitcoin\Common;

interface ClientInterface
{
    public function get(): ?ResponseInterface;
}
