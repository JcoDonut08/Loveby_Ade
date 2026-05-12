@extends('layouts.guest')

@section('title', '500 | Loveby_Ade')
@section('description', 'Server error on Loveby_Ade.')
@section('body_classes', 'min-h-screen bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_30%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_28%),linear-gradient(180deg,#fff3f8_0%,#edf8ff_52%,#fff7f1_100%)] text-slate-900')

@section('content')
    <x-errors.page
        code="500"
        title="Server error"
        message="Something broke while loading this page. The request did not finish cleanly, so the safest move is to try again from the homepage or send a message to the admin."
        eyebrow="Unexpected issue"
        :image="asset('error-icons/500-cookie-dizzy.svg')"
        image-alt="Dizzy cookie icon"
        :primary-href="route('home')"
        primary-label="Go to homepage"
        :secondary-href="route('contact')"
        secondary-label="Report the issue"
    />
@endsection
