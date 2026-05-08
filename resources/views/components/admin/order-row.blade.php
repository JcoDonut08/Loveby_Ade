@props([
    'order',
    'customer',
    'item',
    'total',
    'payment',
    'status',
    'tone' => 'pink',
])

@php
    $statusClass = match ($tone) {
        'blue' => 'bg-love-blue-200 text-[#23445c]',
        'green' => 'bg-emerald-100 text-emerald-600',
        'rose' => 'bg-rose-100 text-rose-500',
        'amber' => 'bg-amber-100 text-amber-700',
        default => 'bg-love-pink-100 text-love-pink-500',
    };
@endphp

<tr {{ $attributes->merge(['data-order-row' => 'true']) }}>
    <td class="py-5 pr-5 font-extrabold text-[#3b1728]">{{ $order }}</td>
    <td class="px-5 py-5 font-bold text-[#512438]">{{ $customer }}</td>
    <td class="px-5 py-5 font-medium text-[#9a6c7b]">{{ $item }}</td>
    <td class="px-5 py-5 font-extrabold text-[#3b1728]">{{ $total }}</td>
    <td class="px-5 py-5 font-medium text-[#512438]">{{ $payment }}</td>
    <td class="px-5 py-5">
        <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-extrabold {{ $statusClass }}">
            <span class="h-2 w-2 rounded-full bg-current"></span>
            {{ $status }}
        </span>
    </td>
    <td class="py-5 pl-5">
        <div class="flex min-w-80 flex-wrap gap-2">
            <button class="rounded-full border border-love-pink-100 px-3 py-1.5 text-xs font-extrabold text-[#512438] transition hover:bg-love-pink-100" type="button">View</button>
            <button class="rounded-full border border-love-blue-100 bg-love-blue-100/70 px-3 py-1.5 text-xs font-extrabold text-[#23445c] transition hover:bg-love-blue-200" type="button">Update Status</button>
            <button class="rounded-full border border-rose-100 px-3 py-1.5 text-xs font-extrabold text-rose-500 transition hover:bg-rose-50" type="button">Cancel</button>
            <button class="rounded-full border border-amber-100 px-3 py-1.5 text-xs font-extrabold text-amber-700 transition hover:bg-amber-50" type="button">Print Receipt</button>
        </div>
    </td>
</tr>
