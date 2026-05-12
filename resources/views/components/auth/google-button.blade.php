@props([
    'label',
])

<a {{ $attributes->merge(['class' => 'inline-flex w-full items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500']) }} href="{{ route('auth.google.redirect') }}">
    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
        <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-.9 2.4-1.9 3.2l3.1 2.4c1.8-1.7 2.8-4.1 2.8-6.9 0-.7-.1-1.4-.2-2H12Z"/>
        <path fill="#34A853" d="M12 21c2.7 0 4.9-.9 6.6-2.4l-3.1-2.4c-.9.6-2 1-3.5 1-2.7 0-4.9-1.8-5.7-4.2H3.1v2.5A10 10 0 0 0 12 21Z"/>
        <path fill="#4A90E2" d="M6.3 13c-.2-.6-.3-1.3-.3-2s.1-1.4.3-2V6.5H3.1A10 10 0 0 0 2 11c0 1.6.4 3.1 1.1 4.5L6.3 13Z"/>
        <path fill="#FBBC05" d="M12 4.8c1.5 0 2.8.5 3.9 1.5l2.9-2.9C16.9 1.7 14.7 1 12 1A10 10 0 0 0 3.1 6.5L6.3 9c.8-2.5 3-4.2 5.7-4.2Z"/>
    </svg>
    <span>{{ $label }}</span>
</a>
