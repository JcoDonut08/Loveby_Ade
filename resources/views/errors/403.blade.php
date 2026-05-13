@extends('layouts.guest')

@section('title', '403 | Loveby_Ade')
@section('description', 'Access denied on Loveby_Ade.')
@section('body_classes', 'min-h-screen bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_30%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_28%),linear-gradient(180deg,#fff3f8_0%,#edf8ff_52%,#fff7f1_100%)] text-slate-900')

@section('content')
    <x-errors.page
        code="403"
        title="Access denied"
        message="This page is not available for your account right now. Head back to the storefront or contact the admin if you think this should be open."
        eyebrow="Restricted page"
        :image="asset('error-icons/403.png')"
        image-alt="Sad cupcake holding a 403 forbidden sign"
        :primary-href="route('home')"
        primary-label="Back to homepage"
        :secondary-href="route('contact')"
        secondary-label="Contact admin"
    />
@endsection
