<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Order #{{ $request->id }}</title>
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #dc2626; padding-bottom: 20px; }
        .logo { width: 80px; margin-bottom: 10px; }
        .title { font-size: 24px; font-weight: 700; color: #dc2626; margin: 0; }
        .subtitle { font-size: 14px; color: #666; margin: 5px 0 0; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16px; font-weight: 600; color: #dc2626; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 15px; }
        .row { display: flex; margin-bottom: 10px; }
        .label { width: 220px; font-weight: 500; color: #555; }
        .value { flex: 1; }
        .signatures { display: flex; flex-wrap: wrap; justify-content: space-around; margin-top: 40px; gap: 20px; }
        .sig-block { text-align: center; width: 160px; }
        .sig-img { max-width: 140px; max-height: 55px; border: 1px solid #ddd; padding: 5px; }
        .sig-line { margin-top: 10px; border-top: 1px solid #333; width: 100%; }
        .sig-name { font-size: 11px; margin-top: 5px; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-completed { background: #e5e7eb; color: #374151; }
        .requesting-for { padding: 4px 12px; border-radius: 4px; font-size: 13px; }
        .cash-advance { background: #dbeafe; color: #1e40af; }
        .reimbursement { background: #fce7f3; color: #9d174d; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/LOGO.png') }}" alt="DHVSU Logo" class="logo" onerror="this.style.display='none'">
        <h1 class="title">VEHISERVE</h1>
        <p class="subtitle">Don Honorio Ventura State University - Motor Pool Management System</p>
        <p class="subtitle">Travel Order Form - Order #{{ $request->id }}</p>
    </div>

    <div class="section">
        <div class="section-title">Travel Order Details</div>
        <div class="row">
            <span class="label">Request ID:</span>
            <span class="value">#{{ $request->id }}</span>
        </div>
        <div class="row">
            <span class="label">Personnel Name:</span>
            <span class="value">{{ $request->personnel_name }}</span>
        </div>
        <div class="row">
            <span class="label">Official Station:</span>
            <span class="value">{{ $request->official_station }}</span>
        </div>
        <div class="row">
            <span class="label">Destination:</span>
            <span class="value">{{ $request->destination }}</span>
        </div>
        <div class="row">
            <span class="label">Purpose:</span>
            <span class="value">{{ $request->purpose }}</span>
        </div>
        <div class="row">
            <span class="label">Inclusive Date:</span>
            <span class="value">{{ $request->inclusive_date->format('F d, Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Requesting For:</span>
            <span class="value">
                <span class="requesting-for 
                    @if($request->requesting_for === 'Cash Advance') cash-advance
                    @elseif($request->requesting_for === 'Reimbursement') reimbursement
                    @endif">
                    {{ $request->requesting_for }}
                </span>
            </span>
        </div>
        <div class="row">
            <span class="label">Vehicle Request:</span>
            <span class="value">
                <span class="status-badge status-{{ strtolower($request->vehicle_request) }}">
                    {{ $request->vehicle_request }}
                </span>
            </span>
        </div>
        <div class="row">
            <span class="label">Vehicle Status:</span>
            <span class="value">
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $request->vehicle_status)) }}">
                    {{ $request->vehicle_status }}
                </span>
            </span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Approval Workflow</div>
        @foreach($request->approvals as $approval)
        <div class="row">
            <span class="label">{{ $approval->role }}:</span>
            <span class="value">
                @if($approval->status === 'Approved')
                    <span class="status-badge status-approved">Approved</span>
                @elseif($approval->status === 'Rejected')
                    <span class="status-badge status-rejected">Rejected</span>
                @else
                    <span class="status-badge status-pending">Pending</span>
                @endif
                @if($approval->approved_at)
                    <br><small>{{ $approval->approved_at->format('F d, Y g:i A') }}</small>
                @endif
                @if($approval->comment)
                    <br><small>Comment: {{ $approval->comment }}</small>
                @endif
            </span>
        </div>
        @endforeach
    </div>

    @if($request->documents->count() > 0)
    <div class="section">
        <div class="section-title">Attached Documents</div>
        @foreach($request->documents as $doc)
        <div class="row">
            <span class="label">{{ ucfirst(str_replace('_', ' ', $doc->type)) }}:</span>
            <span class="value">{{ $doc->file_name }}</span>
        </div>
        @endforeach
    </div>
    @endif

    <div class="signatures">
        <div class="sig-block">
            @if($request->documents->where('type', 'signature')->first())
            <img src="{{ asset('storage/' . $request->documents->where('type', 'signature')->first()->file_path) }}" alt="Requester Signature" class="sig-img">
            @else
            <div class="sig-line"></div>
            @endif
            <div class="sig-name">{{ $request->personnel_name }}</div>
            <div class="sig-name">Requester</div>
        </div>

        @if($request->documents->where('type', 'dean_signature')->first())
        <div class="sig-block">
            <img src="{{ asset('storage/' . $request->documents->where('type', 'dean_signature')->first()->file_path) }}" alt="Dean Signature" class="sig-img">
            <div class="sig-name">Dean/Director</div>
        </div>
        @endif

        @if($request->documents->where('type', 'vp_signature')->first())
        <div class="sig-block">
            <img src="{{ asset('storage/' . $request->documents->where('type', 'vp_signature')->first()->file_path) }}" alt="VP Signature" class="sig-img">
            <div class="sig-name">Cluster Vice President</div>
        </div>
        @endif

        @if($request->documents->where('type', 'suc_signature')->first())
        <div class="sig-block">
            <img src="{{ asset('storage/' . $request->documents->where('type', 'suc_signature')->first()->file_path) }}" alt="SUC Signature" class="sig-img">
            <div class="sig-name">SUC President</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y g:i A') }} | Vehiserve Motor Pool Management System</p>
        <p>Don Honorio Ventura State University</p>
    </div>
</body>
</html>