<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 18px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #2f3542;
            background: #ffffff;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .receipt {
            padding: 14px 16px 18px;
            background: #ffffff;
            border: 1px solid #ffe7f1;
        }

        .brand {
            text-align: center;
        }

        .logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
            vertical-align: middle;
        }

        .brand-name {
            display: inline-block;
            margin-left: 12px;
            color: #f472a8;
            font-family: DejaVu Serif, serif;
            font-size: 34px;
            font-weight: 700;
            line-height: 1;
            vertical-align: middle;
        }

        .tagline {
            margin-top: 5px;
            color: #512438;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .rule {
            margin: 12px 0 14px;
            border-top: 1px dashed #f9a8cf;
            text-align: center;
        }

        .rule span {
            position: relative;
            top: -8px;
            padding: 0 10px;
            color: #f472a8;
            background: #ffffff;
            font-size: 13px;
        }

        .banner {
            width: 280px;
            margin: 0 auto 10px;
            padding: 7px 14px;
            color: #2f3542;
            background: #ffe7f1;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            text-align: center;
            text-transform: uppercase;
        }

        .receipt-number {
            margin-bottom: 10px;
            color: #9a6c7b;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
            text-align: center;
            text-transform: uppercase;
        }

        .details {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1px dashed #f9c6dd;
        }

        .details > tbody > tr > td {
            width: 50%;
            padding: 4px 12px 6px 0;
            vertical-align: top;
        }

        .detail-row {
            width: 100%;
            margin-bottom: 3px;
        }

        .icon {
            width: 24px;
            height: 24px;
            border-radius: 12px;
            color: #ec4899;
            background: #ffe7f1;
            font-size: 10px;
            font-weight: 800;
            line-height: 24px;
            text-align: center;
        }

        .icon.blue {
            color: #2f6fa9;
            background: #dff5ff;
        }

        .label {
            color: #2f3542;
            font-weight: 800;
        }

        .value {
            margin-top: 2px;
            color: #512438;
            line-height: 1.25;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .items th {
            padding: 5px 8px;
            color: #2f3542;
            background: #ffe7f1;
            border-top: 1px solid #f9c6dd;
            border-bottom: 1px solid #f9c6dd;
            font-size: 9px;
            text-align: left;
            text-transform: uppercase;
        }

        .items td {
            padding: 6px 8px;
            border-bottom: 1px dashed #f9c6dd;
            vertical-align: top;
        }

        .thumb {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            color: #ec4899;
            background: #fff8f6;
            border: 1px solid #ffe7f1;
            font-size: 13px;
            line-height: 28px;
            text-align: center;
        }

        .item-title {
            color: #2f3542;
            font-weight: 800;
        }

        .item-meta {
            margin-top: 1px;
            color: #6b7280;
            font-size: 8px;
            line-height: 1.2;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .item-column {
            width: 52%;
        }

        .quantity-column {
            width: 10%;
        }

        .money-column {
            width: 19%;
            white-space: nowrap;
        }

        .money-cell {
            white-space: nowrap;
        }

        .summary-wrap {
            width: 100%;
            margin-top: 10px;
        }

        .summary-spacer {
            width: 52%;
        }

        .summary {
            width: 48%;
            padding-top: 8px;
            border-top: 1px solid #f9c6dd;
            vertical-align: top;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            padding: 3px 0;
        }

        .discount {
            color: #ec4899;
        }

        .total {
            width: 100%;
            margin-top: 5px;
            color: #2f3542;
            background: #dff5ff;
            font-size: 13px;
            font-weight: 800;
        }

        .total td {
            padding: 7px 10px;
        }

        .thanks {
            margin-top: 10px;
            padding-top: 9px;
            border-top: 1px dashed #f9c6dd;
            text-align: center;
        }

        .thanks-title {
            color: #f472a8;
            font-family: DejaVu Serif, serif;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
        }

        .thanks-subtitle {
            color: #38bdf8;
            font-family: DejaVu Serif, serif;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.1;
        }

        .footnote {
            margin-top: 5px;
            color: #512438;
            line-height: 1.5;
        }

        .heart {
            margin-top: 4px;
            color: #f472a8;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <main class="receipt">
        <header class="brand">
            @if ($logoDataUri)
                <img class="logo" src="{{ $logoDataUri }}" alt="Loveby_Ade logo">
            @endif
            <span class="brand-name">Loveby_Ade</span>
            <div class="tagline">Sweet treats, made with love</div>
            <div class="rule"><span>&hearts;</span></div>
            <div class="banner">Customer Receipt</div>
            <div class="receipt-number">{{ $receiptNumber }}</div>
        </header>

        <table class="details">
            <tr>
                <td>
                    <table class="detail-row">
                        <tr><td class="icon">#</td><td><div class="label">Order ID</div><div class="value">{{ $order->order_number }}</div></td></tr>
                    </table>
                    <table class="detail-row">
                        <tr><td class="icon">D</td><td><div class="label">Date</div><div class="value">{{ $orderedAt }}</div></td></tr>
                    </table>
                    <table class="detail-row">
                        <tr><td class="icon">U</td><td><div class="label">Customer Name</div><div class="value">{{ $order->full_name }}</div></td></tr>
                    </table>
                    <table class="detail-row">
                        <tr><td class="icon">E</td><td><div class="label">Email</div><div class="value">{{ $order->email_address }}</div></td></tr>
                    </table>
                </td>
                <td>
                    <table class="detail-row">
                        <tr><td class="icon blue">P</td><td><div class="label">Delivery Address</div><div class="value">{{ $order->complete_address }}</div></td></tr>
                    </table>
                    <table class="detail-row">
                        <tr><td class="icon blue">$</td><td><div class="label">Payment Method</div><div class="value">{{ $order->payment_method }}</div></td></tr>
                    </table>
                    <table class="detail-row">
                        <tr><td class="icon blue">OK</td><td><div class="label">Payment Status</div><div class="value">{{ $isPaid ? 'Paid' : 'Payment pending' }}</div></td></tr>
                    </table>
                    <table class="detail-row">
                        <tr><td class="icon blue">S</td><td><div class="label">Order Status</div><div class="value">{{ $statusLabel }}</div></td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th class="item-column">Item</th>
                    <th class="center quantity-column">Qty</th>
                    <th class="right money-column">Unit Price</th>
                    <th class="right money-column">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="thumb">&hearts;</td>
                                    <td><div class="item-title">{{ $item->product_title }}</div><div class="item-meta">{{ $item->category }}</div></td>
                                </tr>
                            </table>
                        </td>
                        <td class="center">{{ $item->quantity }}</td>
                        <td class="right money-cell">&#8369;{{ number_format((float) $item->unit_price, 2) }}</td>
                        <td class="right money-cell"><strong>&#8369;{{ number_format((float) $item->line_total, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-wrap">
            <tr>
                <td class="summary-spacer"></td>
                <td class="summary">
                    <table>
                        <tr><td>Subtotal</td><td class="right money-cell">&#8369;{{ number_format((float) $order->subtotal, 2) }}</td></tr>
                        <tr><td>Delivery Fee</td><td class="right money-cell">&#8369;{{ number_format((float) $order->delivery_fee, 2) }}</td></tr>
                        <tr class="discount"><td>Discount</td><td class="right money-cell">-&#8369;{{ number_format((float) $order->discount, 2) }}</td></tr>
                    </table>
                    <table class="total">
                        <tr><td>TOTAL PAID</td><td class="right money-cell">&#8369;{{ number_format((float) $order->total, 2) }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <footer class="thanks">
            <div class="thanks-title">Thank you</div>
            <div class="thanks-subtitle">for your order!</div>
            <div class="footnote">We appreciate your trust in Loveby_Ade. Hope to sweeten your day again soon.</div>
            <div class="heart">&hearts;</div>
        </footer>
    </main>
</body>
</html>
