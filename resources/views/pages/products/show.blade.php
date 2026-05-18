@extends('layouts.guest')

@section('title', $product['title'].' | Loveby_Ade')
@section('description', 'Loveby_Ade product overview for '.$product['title'].'.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff7fb_0%,#eff8ff_52%,#fff8f3_100%)] text-slate-900')

@section('content')
    <div class="relative min-h-screen overflow-x-hidden">
        <x-home.store-header />

        <main class="py-10 sm:py-14">
            <x-product.overview :product="$product" />
            <x-product.reviews :reviews="$reviewItems" :summary="$reviewSummary" />
            <x-product.review-form :product="$product" :can-review="$canReviewProduct" />
            <x-product.recommendations :products="$recommendations" />
        </main>

        <x-home.store-footer />
    </div>
@endsection
