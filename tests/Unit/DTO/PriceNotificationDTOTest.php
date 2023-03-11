<?php

namespace Unit\DTO;

use App\DTO\PriceNotificationDTO;
use PHPUnit\Framework\TestCase;

class PriceNotificationDTOTest extends TestCase
{
    public function test()
    {
        // Arrange
        $email = 'test@email.com';
        $price = 68767.45;

        // Act
        $dto = new PriceNotificationDTO($email, $price);

        //Assert
        $this->assertEquals($email, $dto->email);
        $this->assertEquals($price, $dto->price);
    }
}
