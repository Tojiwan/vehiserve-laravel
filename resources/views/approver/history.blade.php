@extends('layouts.approver')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Approval History</h1>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>ID</th>
                            <th>Requester</th>
                            <th>Decision</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="empty-state">
                                    <p class="empty-state-title">No history available</p>
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
