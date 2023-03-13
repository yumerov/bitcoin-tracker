<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceChangeMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(private readonly float $price)
    {

    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Price Change Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.price-change',
            with: ['price' => $this->price]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
