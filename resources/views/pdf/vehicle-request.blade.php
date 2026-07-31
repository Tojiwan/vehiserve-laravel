<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Request #{{ $request->id }}</title>
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #dc2626; padding-bottom: 20px; }
        .logo { width: 80px; margin-bottom: 10px; }
        .title { font-size: 24px; font-weight: 700; color: #dc2626; margin: 0; }
        .subtitle { font-size: 14px; color: #666; margin: 5px 0 0; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16px; font-weight: 600; color: #dc2626; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 15px; }
        .row { display: flex; margin-bottom: 10px; }
        .label { width: 200px; font-weight: 500; color: #555; }
        .value { flex: 1; }
        .passengers { margin-top: 10px; }
        .passenger-item { padding: 5px 0; border-bottom: 1px solid #f0f0f0; }
        .signatures { display: flex; justify-content: space-around; margin-top: 40px; }
        .sig-block { text-align: center; width: 180px; }
        .sig-img { max-width: 150px; max-height: 60px; border: 1px solid #ddd; padding: 5px; }
        .sig-line { margin-top: 10px; border-top: 1px solid #333; width: 100%; }
        .sig-name { font-size: 12px; margin-top: 5px; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-completed { background: #e5e7eb; color: #374151; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/LOGO.png') }}" alt="DHVSU Logo" class="logo" onerror="this.style.display='none'">
        <h1 class="title">VEHISERVE</h1>
        <p class="subtitle">Don Honorio Ventura State University - Motor Pool Management System</p>
        <p class="subtitle">Vehicle Request Form - Request #{{ $request->id }}</p>
    </div>

    <div class="section">
        <div class="section-title">Request Details</div>
        <div class="row">
            <span class="label">Request ID:</span>
            <span class="value">#{{ $request->id }}</span>
        </div>
        <div class="row">
            <span class="label">Date of Request:</span>
            <span class="value">{{ $request->request_date->format('F d, Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Requesting Personnel:</span>
            <span class="value">{{ $request->requesting_person }}</span>
        </div>
        <div class="row">
            <span class="label">Office/College:</span>
            <span class="value">{{ $request->office_college }}</span>
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
            <span class="label">Departure Date:</span>
            <span class="value">{{ $request->departure_date->format('F d, Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Departure Time:</span>
            <span class="value">{{ $request->departure_time->format('g:i A') }}</span>
        </div>
        <div class="row">
            <span class="label">Number of Passengers:</span>
            <span class="value">{{ $request->num_passengers }}</span>
        </div>
        <div class="row">
            <span class="label">Status:</span>
            <span class="value">
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $request->vehicle_status)) }}">
                    {{ $request->vehicle_status }}
                </span>
            </span>
        </div>
    </div>

    @if($request->passengers->count() > 0)
    <div class="section">
        <div class="section-title">Passengers</div>
        <div class="passengers">
            @foreach($request->passengers as $index => $passenger)
            <div class="passenger-item">{{ $index + 1 }}. {{ $passenger->passenger_name }}</div>
            @endforeach
        </div>
    </div>
    @endif

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
            <div class="sig-name">{{ $request->requesting_person }}</div>
            <div class="sig-name">Requester</div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y g:i A') }} | Vehiserve Motor Pool Management System</p>
        <p>Don Honorio Ventura State University</p>
    </div>
</body>
</html>