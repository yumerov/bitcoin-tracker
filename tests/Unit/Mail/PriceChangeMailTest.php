<?php

namespace Unit\Mail;

use App\Mail\PriceChangeMail;
use Tests\TestCase;

class PriceChangeMailTest extends TestCase
{
    private const VIEW = 'emails.price-change';
    private const PRICE = 68324;

    public function test_class():void
    {
        // Act
        $mail = new PriceChangeMail(self::PRICE);

        // Assert
        $this->assertEquals('Price Change Mail', $mail->envelope()->subject);
        $this->assertEquals(self::VIEW, $mail->content()->text);
        $this->assertIsArray($mail->content()->with);
        $this->assertEquals(self::PRICE, $mail->content()->with['price']);
        $this->assertEmpty($mail->attachments());
    }

    public function test_view(): void
    {
        $data = ['price' => self::PRICE];

        $response = $this->view(self::VIEW, $data);

        $response->assertSeeText('The price of BTC has exceeded the limit of '.self::PRICE.' USD.');
    }
}
