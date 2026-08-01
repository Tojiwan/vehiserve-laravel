@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.reports.vehicle-usage') }}" class="card p-6 hover:bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Vehicle Usage</h3>
            <p class="text-gray-500 mt-1">View vehicle utilization reports</p>
        </a>
        <a href="{{ route('admin.reports.driver-performance') }}" class="card p-6 hover:bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Driver Performance</h3>
            <p class="text-gray-500 mt-1">View driver performance metrics</p>
        </a>
        <div class="card p-6 hover:bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Trip Summary</h3>
            <p class="text-gray-500 mt-1">View trip summary reports</p>
        </div>
    </div>
</div>
@endsection
