<div class="relative">
    <!-- Notification Bell Button -->
    <button wire:click="toggle" class="relative btn btn-ghost btn-icon p-2 text-gray-600 hover:text-gray-900" aria-label="Notifications">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if ($unreadCount > 0)
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    <div class="dropdown-menu w-96 right-0" x-show="$wire.show" x-transition:enter="transition ease-out duration-100" x-transition:leave="transition ease-in duration-75" wire:click.outside="$wire.show = false" x-cloak>
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Notifications</h3>
            @if ($unreadCount > 0)
            <button wire:click="markAllAsRead" class="text-sm text-red-600 hover:text-red-700">Mark all as read</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse ($notifications as $notification)
            <div wire:click="markAsRead({{ $notification->id }})" class="px-4 py-3 hover:bg-gray-50 border-b border-gray-50 cursor-pointer transition-colors {{ !$notification->read_at ? 'bg-blue-50' : '' }}">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12a6 6 0 01-6 6H6a6 6 0 01-6-6H0"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->data['message'] ?? 'New notification' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if (!$notification->read_at)
                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="mt-2 text-gray-500">No notifications</p>
            </div>
            @endforelse
        </div>

        @if ($notifications->count() > 0)
        <div class="px-4 py-3 border-t border-gray-100">
            <a href="{{ route('notifications.index') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">View all notifications</a>
        </div>
        @endif
    </div>
</div>