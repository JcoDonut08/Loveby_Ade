<x-mail::message>
# New contact message

<x-mail::panel>
Concern: {{ $contactMessage['concern'] }}

Order number: {{ ($contactMessage['order_number'] ?? null) ?: 'Not provided' }}
</x-mail::panel>

Name: {{ $contactMessage['name'] }}

Email: {{ $contactMessage['email'] }}

Message:

{{ $contactMessage['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
