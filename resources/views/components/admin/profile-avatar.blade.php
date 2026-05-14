@props([
    'user' => auth()->user(),
])

@php
    $profilePhotoUrl = $user?->profilePhotoUrl();
    $name = $user?->name ?? 'Admin';
    $initials = collect(explode(' ', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('') ?: 'AD';
@endphp

<span {{ $attributes->merge(['class' => 'flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-love-pink-400 font-extrabold text-white']) }}>
    <img class="{{ $profilePhotoUrl ? '' : 'hidden' }} h-full w-full object-cover" src="{{ $profilePhotoUrl ?? '' }}" alt="{{ $name }} profile photo" data-profile-photo-preview-image>
    <span class="{{ $profilePhotoUrl ? 'hidden' : '' }}" data-profile-photo-preview-fallback>{{ $initials }}</span>
</span>
