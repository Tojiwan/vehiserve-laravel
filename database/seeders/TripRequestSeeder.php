<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TripRequest;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\Approval;
use App\Models\Document;
use App\Models\Booking;
use App\Enums\TripRequestStatus;
use Carbon\Carbon;

class TripRequestSeeder extends Seeder
{
    public function run(): void
    {
        // Keep seeding idempotent: remove previously seeded trip request data
        Approval::where('approvable_type', TripRequest::class)->delete();
        Booking::whereNotNull('trip_request_id')->delete();
        TripRequest::query()->delete();
        Vehicle::where('status', 'On Trip')->update(['status' => 'Available']);
        Driver::where('status', 'On Trip')->update(['status' => 'Available']);

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'User');
        })->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users with "User" role found. Skipping TripRequestSeeder.');
            return;
        }

        $vehicles = Vehicle::all();
        $drivers = Driver::where('status', 'Available')->get();

        $requests = [
            // 1. PENDING DEAN (just submitted)
            [
                'user' => $users->first(),
                'personnel_name' => 'Juan M. Dela Cruz',
                'official_station' => 'College of Engineering and Architecture',
                'destination' => 'Bataan Peninsula State University, Balanga Campus',
                'purpose' => 'Attendance to 2025 Regional Engineering Conference and Research Presentation',
                'inclusive_date' => Carbon::today()->addDays(5),
                'requesting_for' => 'Cash Advance',
                'departure_date' => Carbon::today()->addDays(5),
                'departure_time' => '06:00',
                'return_date' => Carbon::today()->addDays(7),
                'num_passengers' => 8,
                'status' => TripRequestStatus::PENDING_DEAN->value,
                'passengers' => [
                    'Maria S. Santos', 'Pedro L. Reyes', 'Ana M. Torres',
                    'Carlos R. Mendoza', 'Luisa P. Garcia', 'Roberto V. Cruz',
                    'Elena D. Bautista', 'Fernando G. Ramos'
                ],
            ],
            // 2. VEHICLE AVAILABLE (Motor Pool assigned vehicle)
            [
                'user' => $users->first(),
                'personnel_name' => 'Dr. Patricia S. Lim',
                'official_station' => 'College of Business Studies',
                'destination' => 'Subic Bay Freeport Zone, Olongapo City',
                'purpose' => 'Industry Immersion Program for Business Administration Students',
                'inclusive_date' => Carbon::today()->addDays(3),
                'requesting_for' => 'Reimbursement',
                'departure_date' => Carbon::today()->addDays(3),
                'departure_time' => '07:00',
                'return_date' => Carbon::today()->addDays(4),
                'num_passengers' => 14,
                'status' => 'Vehicle Assigned',
                'passengers' => [
                    'Mark A. Villanueva', 'Christine T. Uy', 'Ronald E. Tan',
                    'Michelle K. Sy', 'Anthony R. Chua', 'Grace L. Ong',
                    'Henry C. Lim', 'Diana S. Co', 'Patrick M. Yu',
                    'Samantha N. Go', 'Kevin P. Tan', 'Rachel A. Dee',
                    'Jonathan K. Wong', 'Vanessa R. Chua'
                ],
            ],
            // 3. PENDING DEAN (Motor Pool approved, waiting for Dean)
            [
                'user' => $users->first(),
                'personnel_name' => 'Prof. Ricardo A. Santos',
                'official_station' => 'College of Education',
                'destination' => 'DepEd Regional Office III, San Fernando, Pampanga',
                'purpose' => 'Curriculum Alignment Workshop for K-12 Program',
                'inclusive_date' => Carbon::today()->addDays(10),
                'requesting_for' => 'Cash Advance',
                'departure_date' => Carbon::today()->addDays(10),
                'departure_time' => '06:30',
                'return_date' => Carbon::today()->addDays(11),
                'num_passengers' => 6,
                'status' => TripRequestStatus::PENDING_DEAN->value,
                'passengers' => [
                    'Ma. Teresa B. Cruz', 'Antonio V. Dela Rosa', 'Cecilia M. Aquino',
                    'Ramon T. Bautista', 'Lourdes S. Villanueva', 'Felipe R. Morales'
                ],
            ],
            // 4. APPROVED BY DEAN (waiting for VP)
            [
                'user' => $users->first(),
                'personnel_name' => 'Engr. Manuel G. Torres',
                'official_station' => 'College of Industrial Technology',
                'destination' => 'Philippine Science High School, Clark Freeport Zone',
                'purpose' => 'Technical Skills Competition - Regional Level',
                'inclusive_date' => Carbon::today()->addDays(15),
                'requesting_for' => 'Reimbursement',
                'departure_date' => Carbon::today()->addDays(15),
                'departure_time' => '05:00',
                'return_date' => Carbon::today()->addDays(16),
                'num_passengers' => 10,
                'status' => 'Approved by Dean',
                'passengers' => [
                    'John Carlo M. Reyes', 'Patricia Anne S. Cruz', 'Michael Angelo D. Santos',
                    'Kathleen Mae T. Garcia', 'Adrian Paul V. Lopez', 'Stephanie Joy R. Mendoza',
                    'Christian Mark A. Torres', 'Angelica Rose P. Aquino', 'Francis Neil B. Cruz',
                    'Michelle Anne L. Bautista'
                ],
            ],
            // 5. APPROVED BY VP (waiting for SUC)
            [
                'user' => $users->first(),
                'personnel_name' => 'Dr. Elena M. Bautista',
                'official_station' => 'College of Hospitality and Tourism Management',
                'destination' => 'Clark International Airport / Nayong Pilipino',
                'purpose' => 'Tourism Industry Exposure Tour for CHTM Students',
                'inclusive_date' => Carbon::today()->addDays(20),
                'requesting_for' => 'Cash Advance',
                'departure_date' => Carbon::today()->addDays(20),
                'departure_time' => '07:30',
                'return_date' => Carbon::today()->addDays(21),
                'num_passengers' => 25,
                'status' => 'Approved by Vice President',
                'passengers' => [
                    'Andrea Louise C. Santos', 'Ralph Lauren M. Cruz', 'Catherine Nicole V. Torres',
                    'John Robert A. Reyes', 'Maria Isabella P. Garcia', 'Miguel Antonio L. Santos',
                    'Patricia Anne M. Cruz', 'Ralph Kenneth D. Torres', 'Catherine Marie V. Reyes',
                    'John Michael P. Garcia', 'Miguel Carlos L. Santos', 'Patricia May M. Cruz',
                    'Ralph Christian D. Torres', 'Catherine Marie V. Reyes', 'John Michael P. Garcia',
                    'Miguel Rafael L. Santos', 'Patricia Anne M. Cruz', 'Ralph Kenneth D. Torres',
                    'Catherine Marie V. Reyes', 'John Michael P. Garcia', 'Miguel Angelo L. Santos',
                    'Patricia Anne M. Cruz', 'Ralph Kenneth D. Torres', 'Catherine Marie V. Reyes',
                    'John Michael P. Garcia', 'Miguel Angelo L. Santos'
                ],
            ],
            // 5. PENDING FINAL MP APPROVAL (all approvals done, waiting for final MP)
            [
                'user' => $users->first(),
                'personnel_name' => 'Prof. Alexander R. Mendoza',
                'official_station' => 'College of Computing Studies',
                'destination' => 'UP Diliman, Quezon City',
                'purpose' => 'National IT Skills Olympiad - Student Delegation',
                'inclusive_date' => Carbon::today()->addDays(25),
                'requesting_for' => 'Cash Advance',
                'departure_date' => Carbon::today()->addDays(25),
                'departure_time' => '04:00',
                'return_date' => Carbon::today()->addDays(27),
                'num_passengers' => 12,
                'status' => TripRequestStatus::PENDING_FINAL_MP->value,
                'passengers' => [
                    'Kevin J. Santos', 'Alyssa M. Cruz', 'Gabriel T. Torres',
                    'Samantha R. Reyes', 'Nathaniel P. Garcia', 'Victoria A. Santos',
                    'Christian L. Cruz', 'Gabrielle M. Torres', 'Nathaniel V. Reyes',
                    'Samantha P. Garcia', 'Christian D. Santos', 'Victoria T. Cruz'
                ],
            ],
            // 6. COMPLETED (fully approved, trip done)
            [
                'user' => $users->first(),
                'personnel_name' => 'Dr. Carmen L. Villanueva',
                'official_station' => 'College of Arts and Sciences',
                'destination' => 'National Museum of the Philippines, Manila',
                'purpose' => 'Educational Field Trip - Art Appreciation & Philippine History Classes',
                'inclusive_date' => Carbon::today()->subDays(10),
                'requesting_for' => 'Reimbursement',
                'departure_date' => Carbon::today()->subDays(15),
                'departure_time' => '06:00',
                'return_date' => Carbon::today()->subDays(14),
                'num_passengers' => 28,
                'status' => TripRequestStatus::COMPLETED->value,
                'passengers' => [
                    'Andrea C. Santos', 'Ralph M. Cruz', 'Catherine T. Torres',
                    'John R. Reyes', 'Maria P. Garcia', 'Miguel L. Santos',
                    'Patricia M. Cruz', 'Ralph D. Torres', 'Catherine V. Reyes',
                    'John P. Garcia', 'Miguel L. Santos', 'Patricia M. Cruz',
                    'Ralph D. Torres', 'Catherine V. Reyes', 'John P. Garcia',
                    'Miguel L. Santos', 'Patricia M. Cruz', 'Ralph D. Torres',
                    'Catherine V. Reyes', 'John P. Garcia', 'Miguel L. Santos',
                    'Patricia M. Cruz', 'Ralph D. Torres', 'Catherine V. Reyes',
                    'John P. Garcia', 'Miguel L. Santos', 'Patricia M. Cruz',
                    'Ralph D. Torres', 'Catherine V. Reyes'
                ],
            ],
            // 7. NO VEHICLE AVAILABLE (rejected at Motor Pool)
            [
                'user' => $users->first(),
                'personnel_name' => 'Prof. Benjamin T. Cruz',
                'official_station' => 'Graduate School',
                'destination' => 'University of the Philippines, Los Baños',
                'purpose' => 'Research Collaboration Meeting with UPLB Faculty',
                'inclusive_date' => Carbon::today()->addDays(2),
                'requesting_for' => 'N/A',
                'departure_date' => Carbon::today()->addDays(2),
                'departure_time' => '05:00',
                'return_date' => Carbon::today()->addDays(3),
                'num_passengers' => 4,
                'status' => 'No Vehicle Available',
                'passengers' => [
                    'Dr. Maria Elena S. Cruz', 'Dr. Roberto A. Santos', 'Dr. Patricia M. Torres', 'Dr. Jonathan R. Reyes'
                ],
            ],
            // 8. REJECTED BY DEAN
            [
                'user' => $users->first(),
                'personnel_name' => 'Ms. Rosanna P. Garcia',
                'official_station' => 'Senior High School',
                'destination' => 'Enchanted Kingdom, Sta. Rosa, Laguna',
                'purpose' => 'SHS Year-End Educational Field Trip',
                'inclusive_date' => Carbon::today()->addDays(45),
                'requesting_for' => 'N/A',
                'departure_date' => Carbon::today()->addDays(45),
                'departure_time' => '06:00',
                'return_date' => Carbon::today()->addDays(45),
                'num_passengers' => 50,
                'status' => TripRequestStatus::REJECTED_DEAN->value,
                'passengers' => array_map(fn($i) => "Student $i", range(1, 50)),
            ],
            // 9. REJECTED BY VP
            [
                'user' => $users->first(),
                'personnel_name' => 'Prof. Alexander R. Mendoza',
                'official_station' => 'College of Engineering and Architecture',
                'destination' => 'Singapore / Malaysia Study Tour',
                'purpose' => 'International Benchmarking for CEA Programs',
                'inclusive_date' => Carbon::today()->addDays(60),
                'requesting_for' => 'Cash Advance',
                'departure_date' => Carbon::today()->addDays(60),
                'departure_time' => '04:00',
                'return_date' => Carbon::today()->addDays(65),
                'num_passengers' => 20,
                'status' => TripRequestStatus::REJECTED_VP->value,
                'passengers' => array_map(fn($i) => "Faculty $i", range(1, 20)),
            ],
            // 10. CANCELLED BY USER
            [
                'user' => $users->first(),
                'personnel_name' => 'Engr. Michael S. Torres',
                'official_station' => 'College of Engineering and Architecture',
                'destination' => 'Baguio City, Benguet',
                'purpose' => 'Site Inspection for Proposed Satellite Campus',
                'inclusive_date' => Carbon::today()->subDays(5),
                'requesting_for' => 'Reimbursement',
                'departure_date' => Carbon::today()->subDays(5),
                'departure_time' => '04:00',
                'return_date' => Carbon::today()->subDays(3),
                'num_passengers' => 6,
                'status' => TripRequestStatus::CANCELLED->value,
                'passengers' => [
                    'Arch. Roberto M. Santos', 'Engr. Patricia L. Cruz', 'Engr. Michael A. Reyes',
                    'Engr. Catherine T. Torres', 'Engr. John P. Garcia', 'Engr. Miguel L. Santos'
                ],
            ],
        ];

        $createdCount = 0;

        foreach ($requests as $reqData) {
            $passengers = $reqData['passengers'];
            unset($reqData['passengers']);

            $user = $reqData['user'];
            unset($reqData['user']);

            $request = TripRequest::create(array_merge($reqData, [
                'user_ID' => $user->id,
            ]));

            // Create passengers
            foreach ($passengers as $pName) {
                Passenger::create([
                    'request_id' => $request->id,
                    'passenger_name' => $pName,
                ]);
            }

            // Create approval chain based on status
            $this->createApprovalChain($request);

            $createdCount++;
        }

        $this->command->info("Seeded {$createdCount} trip requests with passengers and approval chains.");
    }

    protected function createApprovalChain(TripRequest $request): void
    {
        $status = $request->status;
        $stages = ['Dean', 'Vice President', 'SUC President', 'Motor Pool'];

        $stageOrder = [
            TripRequestStatus::PENDING_DEAN->value => 0,
            TripRequestStatus::APPROVED_DEAN->value => 0,
            TripRequestStatus::REJECTED_DEAN->value => 0,
            TripRequestStatus::PENDING_VP->value => 1,
            TripRequestStatus::APPROVED_VP->value => 1,
            TripRequestStatus::REJECTED_VP->value => 1,
            TripRequestStatus::PENDING_SUC->value => 2,
            TripRequestStatus::APPROVED_SUC->value => 2,
            TripRequestStatus::REJECTED_SUC->value => 2,
            TripRequestStatus::PENDING_MOTOR_POOL->value => 3,
            TripRequestStatus::PENDING_FINAL_MP->value => 3,
            TripRequestStatus::VEHICLE_ASSIGNED->value => 3,
            TripRequestStatus::NO_VEHICLE_AVAILABLE->value => 3,
            TripRequestStatus::COMPLETED->value => 3,
        ];

        $rejectedByRole = null;
        foreach ([
            TripRequestStatus::REJECTED_DEAN->value => 'Dean',
            TripRequestStatus::REJECTED_VP->value => 'Vice President',
            TripRequestStatus::REJECTED_SUC->value => 'SUC President',
            TripRequestStatus::NO_VEHICLE_AVAILABLE->value => 'Motor Pool',
        ] as $rejectedStatus => $role) {
            if ($status === $rejectedStatus) {
                $rejectedByRole = $role;
                break;
            }
        }

        $approvedAtStage = null;
        foreach ([
            TripRequestStatus::APPROVED_DEAN->value => 0,
            TripRequestStatus::APPROVED_VP->value => 1,
            TripRequestStatus::APPROVED_SUC->value => 2,
        ] as $approvedStatus => $order) {
            if ($status === $approvedStatus) {
                $approvedAtStage = $order;
                break;
            }
        }

        $currentStageOrder = $stageOrder[$status] ?? 0;

        foreach ($stages as $index => $role) {
            $approvalStatus = 'Waiting';

            if ($rejectedByRole) {
                if ($index < array_search($rejectedByRole, $stages)) {
                    $approvalStatus = 'Approved';
                } elseif ($role === $rejectedByRole) {
                    $approvalStatus = 'Rejected';
                } else {
                    $approvalStatus = 'Cancelled';
                }
            } elseif (in_array($status, [
                TripRequestStatus::CANCELLED->value,
                TripRequestStatus::REJECTED->value,
            ])) {
                $approvalStatus = 'Cancelled';
            } elseif ($approvedAtStage !== null) {
                if ($index <= $approvedAtStage) {
                    $approvalStatus = 'Approved';
                } elseif ($index === $approvedAtStage + 1) {
                    $approvalStatus = 'Pending';
                }
            } elseif ($index < $currentStageOrder) {
                $approvalStatus = 'Approved';
            } elseif ($index === $currentStageOrder && in_array($status, [
                TripRequestStatus::VEHICLE_ASSIGNED->value,
                TripRequestStatus::COMPLETED->value,
            ])) {
                $approvalStatus = 'Approved';
            } elseif ($index === $currentStageOrder) {
                $approvalStatus = 'Pending';
            }

            Approval::create([
                'approvable_type' => TripRequest::class,
                'approvable_id' => $request->id,
                'user_ID' => in_array($approvalStatus, ['Approved', 'Rejected', 'Pending'])
                    ? User::role($role)->inRandomOrder()->first()?->id
                    : null,
                'role' => $role,
                'status' => $approvalStatus,
                'approved_at' => in_array($approvalStatus, ['Approved', 'Rejected'])
                    ? Carbon::now()->subDays(rand(1, 5))
                    : null,
            ]);
        }
    }
}