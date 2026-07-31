<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\TravelRequest;
use App\Models\Document;
use App\Services\TravelRequestService;
use App\Services\ApprovalWorkflowService;

#[Layout('layouts.user')]
class TravelRequestForm extends Component
{
    use WithFileUploads;

    public $personnel_name;
    public $official_station;
    public $destination;
    public $purpose;
    public $inclusive_date;
    public $requesting_for = 'N/A';
    public $vehicle_request = 'N/A';
    public $signature;
    public $valid_id;

    protected $rules = [
        'personnel_name' => 'required|string|max:255',
        'official_station' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'purpose' => 'required|string|max:1000',
        'inclusive_date' => 'required|date|after_or_equal:today',
        'requesting_for' => 'required|in:Cash Advance,Reimbursement,N/A',
        'vehicle_request' => 'required|in:Yes,No,N/A',
        'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'valid_id' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ];

    public function mount(): void
    {
        $this->inclusive_date = now()->format('Y-m-d');
        $this->personnel_name = auth()->user()->name;
    }

    public function submit(): void
    {
        $this->validate();

        $data = [
            'personnel_name' => $this->personnel_name,
            'official_station' => $this->official_station,
            'destination' => $this->destination,
            'purpose' => $this->purpose,
            'inclusive_date' => $this->inclusive_date,
            'requesting_for' => $this->requesting_for,
            'vehicle_request' => $this->vehicle_request,
        ];

        $documents = [];
        if ($this->signature) {
            $documents['signature'] = $this->signature;
        }
        if ($this->valid_id) {
            $documents['valid_id'] = $this->valid_id;
        }

        $service = new TravelRequestService();
        $request = $service->create($data, $documents);

        // Initialize approval workflow
        $workflow = new ApprovalWorkflowService();
        $workflow->submitTravelRequest($request);

        session()->flash('success', 'Travel request submitted successfully!');
        $this->redirect(route('user.travel-requests'));
    }

    public function render()
    {
        return view('livewire.user.travel-request-form');
    }
}