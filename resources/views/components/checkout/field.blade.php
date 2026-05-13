@props([
    'id',
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'textarea' => false,
    'value' => '',
])

<div>
    <label class="block text-sm font-extrabold text-slate-700" for="{{ $id }}">{{ $label }}</label>
    @if ($textarea)
        <textarea
            class="mt-2 min-h-28 w-full rounded-xl border border-love-pink-100 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100"
            id="{{ $id }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            @required($required)
            data-checkout-input="{{ $name }}"
        >{{ $value }}</textarea>
    @else
        <input
            class="mt-2 w-full rounded-xl border border-love-pink-100 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100"
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            @required($required)
            data-checkout-input="{{ $name }}"
        >
    @endif
</div>
