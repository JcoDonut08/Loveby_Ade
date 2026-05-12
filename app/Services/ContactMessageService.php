<?php

namespace App\Services;

use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class ContactMessageService
{
    /**
     * @param  array{name: string, email: string, concern: string, order_number?: string|null, message: string}  $contactMessage
     */
    public function send(array $contactMessage): void
    {
        $contactMessage['order_number'] ??= null;

        Mail::to((string) config('mail.from.address'))->send(new ContactMessageMail($contactMessage));
    }
}
