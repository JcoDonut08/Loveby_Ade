@props([
    'id',
    'name',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'autocomplete' => null,
    'icon' => null,
])

@php
    $errorMessage = $errors->first($name);
    $hasError = filled($errorMessage);
    $inputType = $type === 'password' ? 'password' : $type;
    $defaultValue = $inputType === 'password' ? null : old($name);
@endphp

<label class="block text-sm font-semibold text-slate-700" for="{{ $id }}">
    <span>{{ $label }}</span>
    <span class="relative mt-1.5 block">
        @if ($icon === 'email')
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.75h16A1.25 1.25 0 0 1 21.25 8v8A1.25 1.25 0 0 1 20 17.25H4A1.25 1.25 0 0 1 2.75 16V8A1.25 1.25 0 0 1 4 6.75Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.5 8 7.7 5.5a1.4 1.4 0 0 0 1.6 0L20.5 8" />
                </svg>
            </span>
        @elseif ($icon === 'user')
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 19.25a7.25 7.25 0 0 1 14.5 0" />
                </svg>
            </span>
        @elseif ($icon === 'password')
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.75 10V8a4.25 4.25 0 1 1 8.5 0v2" />
                    <rect x="4.75" y="10" width="14.5" height="9.5" rx="2.25" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        @endif

        <input
            autocomplete="{{ $autocomplete }}"
            class="w-full rounded-2xl border bg-white py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-4 {{ $icon ? 'px-12' : 'px-4' }} {{ $type === 'password' ? 'pr-12' : '' }} {{ $hasError ? 'border-red-300 focus:border-red-300 focus:ring-red-100' : 'border-slate-200 focus:border-love-pink-300 focus:ring-love-pink-100' }}"
            id="{{ $id }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            type="{{ $inputType }}"
            @if (filled($defaultValue)) value="{{ $defaultValue }}" @endif
        >

        @if ($type === 'password')
            <button
                aria-label="Show password"
                aria-pressed="false"
                class="absolute inset-y-0 right-4 flex items-center text-slate-400 transition hover:text-love-pink-500"
                data-password-target="{{ $id }}"
                data-password-toggle
                type="button"
            >
                <svg class="h-5 w-5" data-password-icon="show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.25-6 9.75-6 9.75 6 9.75 6-3.25 6-9.75 6-9.75-6-9.75-6Z" />
                    <circle cx="12" cy="12" r="3.25" />
                </svg>
                <svg class="hidden h-5 w-5" data-password-icon="hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3 21 21" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 5.18A10.97 10.97 0 0 1 12 5.1c6.5 0 9.75 6 9.75 6a16.16 16.16 0 0 1-4.08 4.61" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.12 14.12A3 3 0 0 1 9.88 9.88" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.32 6.32A16.55 16.55 0 0 0 2.25 12s3.25 6 9.75 6a10.9 10.9 0 0 0 4.03-.74" />
                </svg>
            </button>
        @endif
    </span>

    @if ($hasError)
        <span class="mt-1.5 block text-xs font-medium text-red-500">{{ $errorMessage }}</span>
    @endif
</label>
