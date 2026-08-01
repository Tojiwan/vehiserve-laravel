@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Approval Detail</h1>
        <a href="{{ route('staff.approval-queue') }}" class="btn btn-secondary">Back to Queue</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p>Approval detail view for {{ $type }} #{{ $id }}</p>
            <!-- TODO: Implement approval detail view -->
        </div>
    </div>
</div>
@endsection
