<?php

namespace App\Mail;

use App\Services\OtpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly string $purpose,
    ) {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine(),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.auth.otp-code',
            with: [
                'code' => $this->code,
                'purposeLabel' => $this->purposeLabel(),
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

    private function subjectLine(): string
    {
        return match ($this->purpose) {
            OtpService::PURPOSE_REGISTRATION => 'Verify your Loveby_Ade account',
            OtpService::PURPOSE_PASSWORD_RESET => 'Reset your Loveby_Ade password',
            default => 'Your Loveby_Ade verification code',
        };
    }

    private function purposeLabel(): string
    {
        return match ($this->purpose) {
            OtpService::PURPOSE_REGISTRATION => 'finish creating your account',
            OtpService::PURPOSE_PASSWORD_RESET => 'reset your password',
            default => 'continue',
        };
    }
}
