@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Trips</h1>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Trip ID</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Destination</th>
                            <th>Departure</th>
                            <th>Return</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="empty-state">
                                    <p class="empty-state-title">No trips scheduled</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
