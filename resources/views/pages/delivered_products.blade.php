@extends('layouts.guest')

@section('title', 'Delivered Products | Loveby_Ade')
@section('description', 'View delivered Loveby_Ade products.')
@section('body_classes', 'bg-white text-slate-900')

@php
    $stages = [];
    $orders = $orders ?? collect();
@endphp

@section('content')
    <div class="min-h-screen">
        <x-home.store-header />

        <main class="mx-auto max-w-[88rem] px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-[1.25rem] border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-extrabold text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <section class="grid gap-8" data-customer-delivered-products>
                @forelse ($orders as $order)
                    <x-store.order-card
                        :order="$order"
                        :stages="$stages"
                        :show-progress="false"
                        :show-invoice="false"
                        :show-edit="false"
                    />
                @empty
                    <x-store.empty-state
                        title="No delivered products yet"
                        description="Delivered online orders will show here after you confirm receiving them."
                        action-label="View orders"
                        :action-href="route('orders.index')"
                    />
                @endforelse
            </section>
        </main>

        <x-home.store-footer />
    </div>
@endsection
