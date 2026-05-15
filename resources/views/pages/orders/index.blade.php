@extends('layouts.guest')

@section('title', 'Orders | Loveby_Ade')
@section('description', 'View your Loveby_Ade orders.')
@section('body_classes', 'bg-white text-slate-900')

@php
    $stages = [
        ['step' => 1, 'key' => 'pending', 'label' => 'Pending'],
        ['step' => 2, 'key' => 'preparing', 'label' => 'Mark for Delivery'],
        ['step' => 3, 'key' => 'out_for_delivery', 'label' => 'Out for Delivery'],
        ['step' => 4, 'key' => 'delivered', 'label' => 'Delivered'],
    ];

    $orders = $orders ?? collect();
@endphp

@section('content')
    <div class="min-h-screen">
        <x-home.store-header />

        <main class="mx-auto max-w-[88rem] px-4 py-8 sm:px-6 lg:px-8">
            <section class="grid gap-8" data-customer-orders>
                @forelse ($orders as $order)
                    <x-store.order-card :order="$order" :stages="$stages" show-confirm />
                @empty
                    <x-store.empty-state
                        title="No orders yet"
                        description="Your checkout orders will show here after you place them."
                        action-label="Shop products"
                        :action-href="route('products.index')"
                    />
                @endforelse
            </section>
        </main>

        <x-home.store-footer />
    </div>
@endsection
