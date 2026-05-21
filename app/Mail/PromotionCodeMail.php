<?php

namespace App\Mail;

use App\Models\Promotion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromotionCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Promotion $promotion)
    {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Loveby_Ade promo code: '.$this->promotion->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.promotions.code',
            with: [
                'promotion' => $this->promotion,
                'claimUrl' => route('checkout', ['promo_code' => $this->promotion->code]),
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
