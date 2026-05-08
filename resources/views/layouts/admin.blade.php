<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Loveby_Ade Admin')</title>
    <meta name="description" content="@yield('description', 'Loveby_Ade admin workspace.')">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|playfair-display:600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body_classes', 'bg-[#fff8fb] text-slate-950') min-h-screen font-sans antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[20rem_minmax(0,1fr)]">
        <x-admin.sidebar />

        <div class="min-w-0">
            @yield('content')
        </div>
    </div>
</body>
</html>
