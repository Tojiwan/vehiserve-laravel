<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\TripRequest;
use App\Models\Passenger;
use App\Models\Document;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Services\TripRequestService;
use App\Services\TripRequestWorkflowService;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.user')]
class TripRequestForm extends Component
{
    use WithFileUploads;

    // Travel fields
    public $request_date;
    public $personnel_name;
    public $official_station;
    public $destination;
    public $purpose;
    public $inclusive_date;
    public $requesting_for = 'N/A';

    // Vehicle fields
    public $departure_date;
    public $departure_time;
    public $return_date;
    public $num_passengers = 1;
    public $passengers = [];
    public $vehicle_ID;
    public $driver_ID;

    // File uploads
    public $signature;
    public $valid_id;
    public $memo;

    // Options
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

    public $vehicles = [];
    public $drivers = [];
    public $vehicleConflict = false;

    protected $rules = [
        // Travel fields
        'request_date' => 'required|date|after_or_equal:today',
        'personnel_name' => 'required|string|max:255',
        'official_station' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'purpose' => 'required|string|max:1000',
        'inclusive_date' => 'required|date|after_or_equal:today',
        'requesting_for' => 'required|in:Cash Advance,Reimbursement,N/A',
        
        // Vehicle fields
        'departure_date' => 'required|date|after_or_equal:today',
        'departure_time' => 'required',
        'return_date' => 'required|date|after_or_equal:departure_date',
        'num_passengers' => 'required|integer|min:1|max:50',
        'passengers.*' => 'nullable|string|max:255',
        'vehicle_ID' => 'required|exists:vehicles,vehicle_ID',
        'driver_ID' => 'nullable|exists:drivers,driver_ID',
        
        // Files
        'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'valid_id' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'memo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
    ];

    protected $messages = [
        'request_date.after_or_equal' => 'Request date must be today or in the future.',
        'inclusive_date.after_or_equal' => 'Inclusive date must be today or in the future.',
        'departure_date.after_or_equal' => 'Departure date must be today or in the future.',
        'return_date.after_or_equal' => 'Return date must be on or after departure date.',
    ];

    public function mount(): void
    {
        $this->request_date = now()->format('Y-m-d');
        $this->inclusive_date = now()->format('Y-m-d');
        $this->departure_date = now()->format('Y-m-d');
        $this->return_date = now()->format('Y-m-d');
        $this->departure_time = '08:00';
        $this->personnel_name = auth()->user()->name;
        $this->loadVehiclesAndDrivers();
        $this->initializePassengers();
    }

    public function loadVehiclesAndDrivers(): void
    {
        $this->vehicles = Vehicle::where('status', 'Available')
            ->orderBy('vehicle_name')
            ->get(['vehicle_ID', 'vehicle_name', 'plate_number', 'capacity']);
        
        $this->drivers = Driver::where('status', 'Available')
            ->orderBy('full_name')
            ->get(['driver_ID', 'full_name', 'license_number']);
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

    public function updatedVehicleId(): void
    {
        $this->checkVehicleAvailability();
    }

    public function updatedDepartureDate(): void
    {
        if ($this->return_date && $this->return_date < $this->departure_date) {
            $this->return_date = $this->departure_date;
        }
        $this->checkVehicleAvailability();
    }

    public function updatedReturnDate(): void
    {
        $this->checkVehicleAvailability();
    }

    public function checkVehicleAvailability(): void
    {
        if ($this->vehicle_ID && $this->departure_date && $this->return_date) {
            $conflicts = \App\Models\TripRequest::where('vehicle_ID', $this->vehicle_ID)
                ->whereNotIn('status', [
                    'Cancelled by User',
                    'Rejected',
                    'No Vehicle Available',
                    'Completed',
                ])
                ->where(function ($query) {
                    $query->whereBetween('departure_date', [$this->departure_date, $this->return_date])
                        ->orWhereBetween('return_date', [$this->departure_date, $this->return_date])
                        ->orWhere(function ($q) {
                            $q->where('departure_date', '<=', $this->departure_date)
                                ->where('return_date', '>=', $this->return_date);
                        });
                })
                ->exists();

            $this->vehicleConflict = $conflicts;
        } else {
            $this->vehicleConflict = false;
        }
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->vehicleConflict) {
            $this->addError('vehicle_ID', 'Vehicle is already booked for the selected date range. Please choose another vehicle or date range.');
            return;
        }

        $data = [
            'request_date' => $this->request_date,
            'personnel_name' => $this->personnel_name,
            'official_station' => $this->official_station,
            'destination' => $this->destination,
            'purpose' => $this->purpose,
            'inclusive_date' => $this->inclusive_date,
            'requesting_for' => $this->requesting_for,
            'departure_date' => $this->departure_date,
            'departure_time' => $this->departure_time,
            'return_date' => $this->return_date,
            'num_passengers' => $this->num_passengers,
            'vehicle_ID' => $this->vehicle_ID,
            'driver_ID' => $this->driver_ID,
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

        $service = new \App\Services\TripRequestService();
        $request = $service->create($data, array_filter($this->passengers), $documents);

        // Initialize approval workflow
        $workflow = new \App\Services\TripRequestWorkflowService();
        $workflow->submitTripRequest($request);

        session()->flash('success', 'Trip request submitted successfully!');
        $this->redirect(route('user.document-tracking'));
    }

    public function render()
    {
        return view('livewire.user.trip-request-form');
    }
}