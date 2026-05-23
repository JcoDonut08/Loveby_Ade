<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

$orders = Order::query()->with(['items','user'])->latest()->get();
$customers = User::query()->where(fn($query) => $query->where('role','!=','admin')->orWhereNull('role'))->get();

$orderEvents = $orders->take(3)->map(fn ($order) => [
    'type' => 'order',
    'name' => $order->full_name,
    'message' => 'placed an order',
    'detail' => 'x',
    'time' => $order->created_at,
]);
$customerEvents = $customers->sortByDesc('created_at')->take(2)->map(fn ($user) => [
    'type' => 'register',
    'name' => $user->name,
    'message' => 'registered an account',
    'detail' => 'y',
    'time' => $user->created_at,
]);

echo 'orderEvents class: ' . get_class($orderEvents) . PHP_EOL;
echo 'customerEvents class: ' . get_class($customerEvents) . PHP_EOL;
echo 'orderEvents items class: ' . (count($orderEvents) ? get_class($orderEvents->first()) : 'empty') . PHP_EOL;
echo 'merge class: ' . get_class($orderEvents->merge($customerEvents)) . PHP_EOL;
