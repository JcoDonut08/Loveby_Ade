@extends('layouts.guest')

@section('title', '404 | Loveby_Ade')
@section('description', 'Page not found on Loveby_Ade.')
@section('body_classes', 'min-h-screen bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_30%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_28%),linear-gradient(180deg,#fff3f8_0%,#edf8ff_52%,#fff7f1_100%)] text-slate-900')

@section('content')
    <x-errors.page
        code="404"
        title="Page not found"
        message="The page you tried to open is missing or no longer available. You can head back to the homepage or keep browsing the latest desserts instead."
        eyebrow="Missing page"
        :image="asset('error-icons/404-donut-confused.svg')"
        image-alt="Confused donut icon"
        :primary-href="route('products.index')"
        primary-label="Browse products"
        :secondary-href="route('home')"
        secondary-label="Back to homepage"
    />
@endsection
