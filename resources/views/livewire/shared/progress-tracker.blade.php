<div class="progress-tracker">
    @foreach ($steps as $index => $step)
    <div class="progress-step">
        <div class="progress-step-circle 
            @if($cancelled) text-gray-400
            @elseif($rejectedStep !== null && $index === $rejectedStep) bg-red-600 border-red-600 text-white
            @elseif($index < $currentStep) completed
            @elseif($index === $currentStep) current
            @else pending
            @endif
        ">
            @if($index < $currentStep && $rejectedStep === null && !$cancelled)
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
            @elseif($rejectedStep !== null && $index === $rejectedStep)
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            @elseif($cancelled)
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            @else
            {{ $index + 1 }}
            @endif
        </div>
        <span class="progress-step-label 
            @if($rejectedStep !== null && $index >= $rejectedStep) text-red-600
            @elseif($cancelled) text-gray-500
            @elseif($index === $currentStep) text-red-600 font-medium
            @elseif($index < $currentStep) text-green-600
            @else text-gray-500
            @endif
        ">
            {{ $step['label'] }}
        </span>
    </div>
    @endforeach
</div>