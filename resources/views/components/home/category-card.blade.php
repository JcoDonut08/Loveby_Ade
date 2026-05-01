@props([
    'title',
    'image',
    'href' => '#products',
])

<a class="group relative block overflow-hidden rounded-[2rem] bg-white shadow-[0_28px_60px_-36px_rgba(15,23,42,0.28)] transition duration-300 hover:-translate-y-1.5" href="{{ $href }}">
    <div class="absolute inset-x-0 bottom-0 z-10 h-1/2 bg-[linear-gradient(180deg,rgba(15,23,42,0)_0%,rgba(15,23,42,0.72)_100%)]"></div>
    <img class="h-72 w-full object-cover transition duration-500 group-hover:scale-[1.03] sm:h-80" src="{{ $image }}" alt="{{ $title }} category" loading="lazy">
    <div class="absolute inset-x-0 bottom-0 z-20 p-6">
        <h3 class="font-display text-3xl text-white sm:text-[2rem]">{{ $title }}</h3>
    </div>
</a>
