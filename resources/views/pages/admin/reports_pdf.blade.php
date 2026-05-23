@php
    $formatValue = function (mixed $value, string $type): string {
        if ($type === 'money') {
            return 'PHP '.number_format((float) $value, 2);
        }

        if ($type === 'number') {
            return number_format((float) $value);
        }

        if ($type === 'percent') {
            return number_format((float) $value, 1).'%';
        }

        return (string) $value;
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $report['title'] }} | Loveby_Ade</title>
    <style>
        @page {
            margin: 24px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #3b1728;
            background: #fff8fb;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .sheet {
            position: relative;
            padding: 18px 18px 34px;
            background: #ffffff;
            border: 1px solid #ffe7f1;
        }

        .hero {
            padding: 16px;
            background: #fff1f6;
            border: 1px solid #ffe7f1;
        }

        .hero-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-cell {
            width: 60%;
            vertical-align: top;
        }

        .logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
            vertical-align: middle;
        }

        .brand-name {
            display: inline-block;
            margin-left: 10px;
            color: #f472a8;
            font-family: DejaVu Serif, serif;
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
            vertical-align: middle;
        }

        .tagline {
            margin-top: 7px;
            color: #512438;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 3.5px;
            text-transform: uppercase;
        }

        .report-kind {
            margin-top: 14px;
            color: #ec4899;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.6px;
            text-transform: uppercase;
        }

        .title {
            margin-top: 5px;
            color: #3b1728;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.1;
        }

        .subtitle {
            margin-top: 5px;
            color: #6f4054;
            font-size: 10px;
        }

        .meta-card {
            width: 40%;
            padding: 12px 14px;
            color: #3b1728;
            background: #ffffff;
            border: 1px solid #f9c6dd;
            vertical-align: top;
        }

        .meta-title {
            margin-bottom: 6px;
            color: #ec4899;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .meta-row {
            padding: 2px 0;
            color: #9a6c7b;
            font-size: 8.5px;
            line-height: 1.4;
        }

        .meta-row strong {
            color: #512438;
        }

        .section-title {
            margin: 14px 0 8px;
            padding-bottom: 5px;
            color: #3b1728;
            border-bottom: 1px dashed #f9c6dd;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .summary {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 7px 0;
        }

        .summary td {
            width: 25%;
            padding: 11px 10px;
            background: #fff1f6;
            border: 1px solid #ffe7f1;
            border-top: 4px solid #f472a8;
        }

        .summary-label {
            color: #9a6c7b;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .summary-value {
            margin-top: 4px;
            color: #3b1728;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.15;
        }

        .records {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .records th {
            padding: 7px 6px;
            color: #ffffff;
            background: #ec4899;
            border: 1px solid #ec4899;
            font-size: 8px;
            letter-spacing: 0.4px;
            text-align: left;
            text-transform: uppercase;
        }

        .records td {
            padding: 7px 6px;
            border: 1px solid #ffe7f1;
            color: #512438;
            line-height: 1.25;
            vertical-align: top;
        }

        .records tbody tr:nth-child(even) td {
            background: #fff8fb;
        }

        .records tbody tr:nth-child(odd) td {
            background: #ffffff;
        }

        .right {
            text-align: right;
        }

        .empty {
            padding: 18px;
            color: #9a6c7b;
            text-align: center;
        }

        .footer {
            position: fixed;
            right: 24px;
            bottom: 12px;
            left: 24px;
            color: #9a6c7b;
            border-top: 1px solid #ffe7f1;
            font-size: 8px;
            line-height: 1.4;
            text-align: right;
        }

        .page-number:after {
            content: counter(page);
        }

        .page-count:after {
            content: counter(pages);
        }
    </style>
</head>
<body>
    <main class="sheet">
        <header class="hero">
            <table class="hero-table">
                <tr>
                    <td class="brand-cell">
                        @if ($logoDataUri)
                            <img class="logo" src="{{ $logoDataUri }}" alt="Loveby_Ade logo">
                        @endif
                        <span class="brand-name">Loveby_Ade</span>
                        <div class="tagline">Sweet treats, made with love</div>
                        <div class="report-kind">Admin report</div>
                        <div class="title">{{ $report['title'] }}</div>
                        <div class="subtitle">{{ $report['subtitle'] }}</div>
                    </td>
                    <td class="meta-card">
                        <div class="meta-title">Report details</div>
                        <div class="meta-row"><strong>Range:</strong> {{ $report['range_label'] }}</div>
                        <div class="meta-row"><strong>Generated:</strong> {{ $report['generated_at'] }}</div>
                        <div class="meta-row"><strong>Records:</strong> {{ number_format($rowCount) }}</div>
                        @if ($report['search'] !== '')
                            <div class="meta-row"><strong>Search:</strong> {{ $report['search'] }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </header>

        <div class="section-title">Executive summary</div>
        <table class="summary">
            <tr>
                @foreach ($report['summary'] as $item)
                    <td>
                        <div class="summary-label">{{ $item['label'] }}</div>
                        <div class="summary-value">{{ $item['value'] }}</div>
                    </td>
                @endforeach
            </tr>
        </table>

        <div class="section-title">Detailed records</div>
        <table class="records">
            <thead>
                <tr>
                    @foreach ($report['columns'] as $column)
                        <th>{{ $column['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($report['rows'] as $row)
                    <tr>
                        @foreach ($report['columns'] as $column)
                            @php
                                $type = (string) ($column['type'] ?? 'text');
                            @endphp
                            <td class="{{ in_array($type, ['money', 'number', 'percent'], true) ? 'right' : '' }}">
                                {{ $formatValue($row[$column['key']] ?? '', $type) }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td class="empty" colspan="{{ count($report['columns']) }}">No records found for this report.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Loveby_Ade {{ $report['title'] }} | Page <span class="page-number"></span> of <span class="page-count"></span>
        </div>
    </main>
</body>
</html>
