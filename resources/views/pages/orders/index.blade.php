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

    $orders = [
        [
            'id' => 'Order #LBA-3508',
            'status' => 'pending',
            'status_label' => 'Pending',
            'status_badge' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'placed_at' => 'May 4, 2026',
            'featured_name' => 'Strawberry Cream Cake',
            'featured_image' => 'https://images.unsplash.com/photo-1464305795204-6f5bbfc7fb81?auto=format&fit=crop&w=320&q=80',
            'total' => '&#8369;1,170.00',
            'quantity' => 3,
            'description' => 'Birthday cake set with cookies and cream puffs packed for same-day delivery.',
            'recipient' => 'Mia Reyes',
            'delivery_lines' => ['24 Sampaguita Lane', 'Makati City, Metro Manila'],
            'update_email' => 'm***@example.com',
            'update_phone' => '1********40',
            'current_step' => 1,
            'cancelled_copy' => '',
        ],
        [
            'id' => 'Order #LBA-3506',
            'status' => 'preparing',
            'status_label' => 'Mark for Delivery',
            'status_badge' => 'bg-love-pink-100 text-love-pink-500 ring-love-pink-200',
            'placed_at' => 'May 4, 2026',
            'featured_name' => 'Mini Cake Cups',
            'featured_image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=320&q=80',
            'total' => '&#8369;840.00',
            'quantity' => 6,
            'description' => 'Pastel donut box and mini cake cups packed separately to keep the frosting neat.',
            'recipient' => 'Noah Santos',
            'delivery_lines' => ['12 Mango Street', 'Pasig City, Metro Manila'],
            'update_email' => 'n***@example.com',
            'update_phone' => '1********84',
            'current_step' => 2,
            'cancelled_copy' => '',
        ],
        [
            'id' => 'Order #LBA-3504',
            'status' => 'out_for_delivery',
            'status_label' => 'Out for Delivery',
            'status_badge' => 'bg-love-blue-100 text-[#23445c] ring-love-blue-200',
            'placed_at' => 'May 3, 2026',
            'featured_name' => 'Caramel Brownie Bites',
            'featured_image' => 'https://images.unsplash.com/photo-1519869325930-281384150729?auto=format&fit=crop&w=320&q=80',
            'total' => '&#8369;360.00',
            'quantity' => 3,
            'description' => 'Courier dispatch is active, with your cookie tin and brownie bites handed off for delivery.',
            'recipient' => 'Marcus Chen',
            'delivery_lines' => ['9 Pearl Avenue', 'Mandaluyong City, Metro Manila'],
            'update_email' => 'm***@example.com',
            'update_phone' => '1********01',
            'current_step' => 3,
            'cancelled_copy' => '',
        ],
        [
            'id' => 'Order #LBA-3503',
            'status' => 'delivered',
            'status_label' => 'Delivered',
            'status_badge' => 'bg-emerald-100 text-emerald-600 ring-emerald-200',
            'placed_at' => 'May 3, 2026',
            'featured_name' => 'Mango Cream Cake',
            'featured_image' => 'https://images.unsplash.com/photo-1556913396-7a3c459ef68e?auto=format&fit=crop&w=320&q=80',
            'total' => '&#8369;860.00',
            'quantity' => 1,
            'description' => 'Delivered to the front desk exactly as requested, with the cake kept chilled in transit.',
            'recipient' => 'Sophia Laurent',
            'delivery_lines' => ['51 Rose Street', 'San Juan City, Metro Manila'],
            'update_email' => 's***@example.com',
            'update_phone' => '1********81',
            'current_step' => 4,
            'cancelled_copy' => '',
        ],
        [
            'id' => 'Order #LBA-3501',
            'status' => 'cancelled',
            'status_label' => 'Cancelled',
            'status_badge' => 'bg-rose-100 text-rose-500 ring-rose-200',
            'placed_at' => 'May 2, 2026',
            'featured_name' => 'Cookie Sampler Box',
            'featured_image' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=320&q=80',
            'total' => '&#8369;780.00',
            'quantity' => 2,
            'description' => 'A duplicate order request was paused and cancelled before the kitchen started production.',
            'recipient' => 'Liam OConnor',
            'delivery_lines' => ['33 Poppy Court', 'Paranaque City, Metro Manila'],
            'update_email' => 'l***@example.com',
            'update_phone' => '1********79',
            'current_step' => 0,
            'cancelled_copy' => 'Cancelled reason: Duplicate order.',
        ],
    ];
@endphp

@section('content')
    <div class="min-h-screen">
        <x-home.store-header />

        <main class="mx-auto max-w-[88rem] px-4 py-8 sm:px-6 lg:px-8">
            <section class="grid gap-8" data-customer-orders>
                @foreach ($orders as $order)
                    <x-store.order-card :order="$order" :stages="$stages" />
                @endforeach
            </section>
        </main>

        <x-home.store-footer />
    </div>
@endsection
