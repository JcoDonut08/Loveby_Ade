<x-mail::message>
# A sweet Loveby_Ade treat is waiting

Use promo code **{{ $promotion->code }}** at checkout to get {{ $promotion->discountLabel() }}.

Valid: {{ $promotion->validityLabel() }}

<x-mail::button :url="$claimUrl">
Shop with this code
</x-mail::button>

If the button does not work, enter **{{ $promotion->code }}** in the promo code field during checkout.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
