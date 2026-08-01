<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-500 mt-1">Stay updated with your request status and system alerts</p>
        </div>
        @if (auth()->user()->unreadNotifications()->count() > 0)
        <button wire:click="markAllAsRead" class="btn btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Mark All as Read
        </button>
        @endif
    </div>

    <!-- Filter Tabs -->
    <div class="card">
        <div class="card-body px-6 py-3 border-b border-gray-100">
            <div class="flex gap-2">
                <button wire:click="setFilter('all')" class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                    All
                </button>
                <button wire:click="setFilter('unread')" class="btn {{ $filter === 'unread' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                    Unread <span class="badge badge-danger">{{ auth()->user()->unreadNotifications()->count() }}</span>
                </button>
                <button wire:click="setFilter('read')" class="btn {{ $filter === 'read' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                    Read
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-body p-0">
            @forelse ($notifications as $notification)
            <div wire:click="markAsRead({{ $notification->id }})" class="px-6 py-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-colors {{ !$notification->read_at ? 'bg-blue-50' : '' }}">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12a6 6 0 01-6 6H6a6 6 0 01-6-6H0"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->data['message'] ?? 'New notification' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        @if ($notification->data['url'])
                        <a href="{{ $notification->data['url'] }}" class="text-sm text-red-600 hover:text-red-700 mt-1 inline-block">View Details</a>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if (!$notification->read_at)
                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                        @endif
                        <button wire:click="deleteNotification({{ $notification->id }})" class="text-gray-400 hover:text-red-500 p-1" wire:click.stop>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="mt-2 text-gray-500">{{ $filter === 'unread' ? 'No unread notifications' : 'No notifications yet' }}</p>
            </div>
            @endforelse
        </div>

        {{ $notifications->links() }}
    </div>
</div>