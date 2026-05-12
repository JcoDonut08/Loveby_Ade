<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  array{name: string, email: string, concern: string, order_number?: string|null, message: string}  $contactMessage
     */
    public function __construct(public readonly array $contactMessage)
    {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address($this->contactMessage['email'], $this->contactMessage['name']),
            ],
            subject: '[Loveby_Ade Contact] '.$this->contactMessage['concern'],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact.message',
            with: [
                'contactMessage' => $this->contactMessage,
            ],
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
