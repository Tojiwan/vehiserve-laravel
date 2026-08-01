<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\VehicleRequest;
use App\Models\Passenger;
use App\Models\Document;
use App\Services\VehicleRequestService;
use App\Services\ApprovalWorkflowService;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.user')]
class VehicleRequestForm extends Component
{
    use WithFileUploads;

    public $sidebarOpen = false;
    public $sidebarCollapsed = false;

    public $request_date;
    public $requesting_person;
    public $office_college = '';
    public $destination;
    public $purpose;
    public $departure_date;
    public $departure_time;
    public $return_date;
    public $num_passengers = 1;
    public $passengers = [];
    public $signature;
    public $valid_id;
    public $memo;

    public $offices = [
        'School of Law',
        'Graduate School',
        'College of Arts and Sciences',
        'College of Business Studies',
        'College of Computing Studies',
        'College of Education',
        'College of Engineering and Architecture',
        'College of Hospitality and Tourism Management',
        'College of Industrial Technology',
        'College of Social Sciences and Philosophy',
        'Laboratory High School',
        'Senior High School',
        'Others',
    ];

    public $other_office = '';
    public $showOtherOffice = false;

    protected $rules = [
        'request_date' => 'required|date|after_or_equal:today',
        'requesting_person' => 'required|string|max:255',
        'office_college' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'purpose' => 'required|string|max:1000',
        'departure_date' => 'required|date|after_or_equal:request_date',
        'departure_time' => 'required',
        'return_date' => 'required|date|after_or_equal:departure_date',
        'num_passengers' => 'required|integer|min:1|max:50',
        'passengers.*' => 'nullable|string|max:255',
        'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'valid_id' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'memo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
    ];

    protected $messages = [
        'request_date.after_or_equal' => 'Request date must be today or in the future.',
        'departure_date.after_or_equal' => 'Departure date must be on or after request date.',
        'return_date.after_or_equal' => 'Return date must be on or after departure date.',
    ];

    public function mount(): void
    {
        $this->request_date = now()->format('Y-m-d');
        $this->departure_date = now()->format('Y-m-d');
        $this->return_date = now()->format('Y-m-d');
        $this->departure_time = '08:00';
        $this->initializePassengers();
    }

    public function initializePassengers(): void
    {
        $this->passengers = array_fill(0, $this->num_passengers, '');
    }

    public function updatedNumPassengers(): void
    {
        $this->initializePassengers();
    }

    public function updatedOfficeCollege(): void
    {
        $this->showOtherOffice = $this->office_college === 'Others';
        if (!$this->showOtherOffice) {
            $this->other_office = '';
        }
    }

    public function updatedOtherOffice(): void
    {
        if ($this->showOtherOffice) {
            $this->office_college = $this->other_office;
        }
    }

    public function submit(): void
    {
        $this->validate();

        $data = [
            'request_date' => $this->request_date,
            'requesting_person' => $this->requesting_person,
            'office_college' => $this->office_college,
            'destination' => $this->destination,
            'purpose' => $this->purpose,
            'departure_date' => $this->departure_date,
            'departure_time' => $this->departure_time,
            'return_date' => $this->return_date,
            'num_passengers' => $this->num_passengers,
        ];

        $documents = [];
        if ($this->signature) {
            $documents['signature'] = $this->signature;
        }
        if ($this->valid_id) {
            $documents['valid_id'] = $this->valid_id;
        }
        if ($this->memo) {
            $documents['memo'] = $this->memo;
        }

        $service = new VehicleRequestService();
        $request = $service->create($data, array_filter($this->passengers), $documents);

        // Initialize approval workflow
        $workflow = new ApprovalWorkflowService();
        $workflow->submitVehicleRequest($request);

        session()->flash('success', 'Vehicle request submitted successfully!');
        $this->redirect(route('user.vehicle-requests'));
    }

    public function render()
    {
        return view('livewire.user.vehicle-request-form');
    }
}