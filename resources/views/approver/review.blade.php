@extends('layouts.approver')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Review Request</h1>
        <a href="{{ route('approver.queue') }}" class="btn btn-secondary">Back to Queue</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p>Review {{ $type }} #{{ $id }}</p>
            <!-- TODO: Implement review detail view -->
        </div>
    </div>
</div>
@endsection
