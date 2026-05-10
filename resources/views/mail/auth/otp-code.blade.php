<x-mail::message>
# Your Loveby_Ade OTP

Use this 6-digit code to {{ $purposeLabel }}:

<x-mail::panel>
{{ $code }}
</x-mail::panel>

This code expires in 10 minutes. If you did not request it, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
