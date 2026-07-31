<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Vehiserve') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireScripts
    @livewireStyles
</head>
<body class="font-['Poppins'] bg-gray-50">
    @yield('content')

    <!-- Confirm Modal (Global) -->
    @livewire('shared.confirm-modal')

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"></div>
</body>
</html>