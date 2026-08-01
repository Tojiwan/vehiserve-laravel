<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Vehiserve') }} - Motor Pool</title>

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
    <!-- Header -->
    <header class="header">
        <div class="flex items-center gap-4">
            <button wire:click="$dispatch('sidebar-toggle')" class="lg:hidden btn btn-ghost btn-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/LOGO.png') }}" alt="DHVSU Logo" class="w-8 h-8">
                <span class="text-xl font-bold text-red-700">MOTOR POOL</span>
            </a>
        </div>

        <div class="flex items-center gap-4">
            <!-- Notification Dropdown -->
            @livewire('shared.notification-dropdown')

            <!-- User Menu -->
            <div class="relative">
                <button wire:click="$dispatch('user-menu-toggle')" class="flex items-center gap-2 btn btn-ghost">
                    <div class="avatar avatar-md">
                        @if (auth()->user()->getFirstMediaUrl('avatar'))
                            <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-lg font-semibold text-gray-600">{{ str()->upper(auth()->user()->name[0]) }}</span>
                        @endif
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- User Dropdown -->
                @if (session('user-menu-open'))
                <div class="dropdown-menu" wire:click.outside="$dispatch('user-menu-close')">
                    <a href="{{ route('staff.dashboard') }}" class="dropdown-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('staff.approval-queue') }}" class="dropdown-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Approval Queue
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-red-600 w-full text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" wire:class="{ 'collapsed': sidebarCollapsed }" :class="{ 'lg:translate-x-0': !sidebarCollapsed, '-translate-x-full': sidebarCollapsed }">
        <div class="flex flex-col h-full">
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('staff.dashboard') }}" class="sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="sidebar-label">Dashboard</span>
                </a>

                <a href="{{ route('staff.approval-queue') }}" class="sidebar-link {{ request()->routeIs('staff.approval-queue') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="sidebar-label">Approval Queue</span>
                </a>

                <a href="{{ route('staff.vehicles.index') }}" class="sidebar-link {{ request()->routeIs('staff.vehicles.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h10a1 1 0 011 1v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011-1h3m-3 0a1 1 0 100 2H7"></path>
                    </svg>
                    <span class="sidebar-label">Vehicles</span>
                </a>

                <a href="{{ route('staff.drivers.index') }}" class="sidebar-link {{ request()->routeIs('staff.drivers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="sidebar-label">Drivers</span>
                </a>

                <a href="{{ route('staff.calendar') }}" class="sidebar-link {{ request()->routeIs('staff.calendar') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="sidebar-label">Calendar</span>
                </a>

                <a href="{{ route('staff.trips.index') }}" class="sidebar-link {{ request()->routeIs('staff.trips.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 4-5 4-5s4 3 4 5 3 1 3 1 3-1 3-1 3-5 4-5z"></path>
                    </svg>
                    <span class="sidebar-label">Trips</span>
                </a>

                <a href="{{ route('staff.attendance') }}" class="sidebar-link {{ request()->routeIs('staff.attendance*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="sidebar-label">Attendance</span>
                </a>

                <a href="{{ route('staff.reminders') }}" class="sidebar-link {{ request()->routeIs('staff.reminders') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="sidebar-label">Reminders</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link text-red-600 hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="sidebar-label">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="fixed inset-0 bg-black/50 z-30 lg:hidden" wire:click="$dispatch('sidebar-close')" :class="{ 'hidden': !sidebarOpen }"></div>

    <!-- Main Content -->
    <main class="page-container" wire:class="{ 'lg:pl-16': sidebarCollapsed, 'lg:pl-64': !sidebarCollapsed }">
        <div class="page-content">
            {{ $slot ?? '' }}
            @if (!isset($slot) || $slot === '')
                @yield('content')
            @endif
        </div>
    </main>

    <!-- Confirm Modal -->
    @livewire('shared.confirm-modal')

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"></div>
</body>
</html>