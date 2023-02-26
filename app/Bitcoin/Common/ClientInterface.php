<?php

namespace App\Bitcoin\Common;

interface ClientInterface
{
    function get(): ?ResponseInterface;
}
