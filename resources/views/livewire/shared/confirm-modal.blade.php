<div>
    @if ($show)
    <div class="modal-overlay" wire:click="$dispatch('confirm-modal:close')">
        <div class="modal" wire:click="$event.stopPropagation()">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <button wire:click="close" class="btn btn-ghost btn-sm btn-icon" aria-label="Close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-gray-600">{{ $message }}</p>
            </div>
            <div class="modal-footer">
                <button wire:click="close" class="btn btn-secondary">{{ $cancelText }}</button>
                <button wire:click="confirm" class="btn 
                    @if($variant === 'danger') btn-danger
                    @elseif($variant === 'warning') btn-warning
                    @else btn-primary
                    @endif
                ">{{ $confirmText }}</button>
            </div>
        </div>
    </div>
    @endif
</div>