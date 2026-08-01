<?php

namespace Database\Seeders;

use App\Models\VehicleRequest;
use App\Models\Passenger;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Enums\VehicleRequestStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VehicleRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'User');
        })->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users with "User" role found. Skipping VehicleRequestSeeder.');
            return;
        }

        $vehicles = Vehicle::all();
        $drivers = Driver::where('status', 'Available')->get();

        $requests = [
            // 1. PENDING MOTOR POOL (just submitted)
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(1),
                'requesting_person' => 'Juan M. Dela Cruz',
                'office_college' => 'College of Engineering and Architecture',
                'destination' => 'Bataan Peninsula State University, Balanga Campus',
                'purpose' => 'Attendance to 2025 Regional Engineering Conference and Research Presentation',
                'departure_date' => Carbon::today()->addDays(5),
                'departure_time' => '06:00',
                'return_date' => Carbon::today()->addDays(7),
                'num_passengers' => 8,
                'vehicle_status' => VehicleRequestStatus::PENDING_MOTOR_POOL->value,
                'passengers' => [
                    'Maria S. Santos', 'Pedro L. Reyes', 'Ana M. Torres',
                    'Carlos R. Mendoza', 'Luisa P. Garcia', 'Roberto V. Cruz',
                    'Elena D. Bautista', 'Fernando G. Ramos'
                ],
            ],
            // 2. VEHICLE AVAILABLE (Motor Pool assigned vehicle)
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(5),
                'requesting_person' => 'Dr. Patricia S. Lim',
                'office_college' => 'College of Business Studies',
                'destination' => 'Subic Bay Freeport Zone, Olongapo City',
                'purpose' => 'Industry Immersion Program for Business Administration Students',
                'departure_date' => Carbon::today()->addDays(3),
                'departure_time' => '07:00',
                'return_date' => Carbon::today()->addDays(4),
                'num_passengers' => 14,
                'vehicle_status' => VehicleRequestStatus::VEHICLE_AVAILABLE->value,
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
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(8),
                'requesting_person' => 'Prof. Ricardo A. Santos',
                'office_college' => 'College of Education',
                'destination' => 'DepEd Regional Office III, San Fernando, Pampanga',
                'purpose' => 'Curriculum Alignment Workshop for K-12 Program',
                'departure_date' => Carbon::today()->addDays(10),
                'departure_time' => '06:30',
                'return_date' => Carbon::today()->addDays(11),
                'num_passengers' => 6,
                'vehicle_status' => VehicleRequestStatus::PENDING_DEAN->value,
                'passengers' => [
                    'Ma. Teresa B. Cruz', 'Antonio V. Dela Rosa', 'Cecilia M. Aquino',
                    'Ramon T. Bautista', 'Lourdes S. Villanueva', 'Felipe R. Morales'
                ],
            ],
            // 4. APPROVED BY DEAN (waiting for VP)
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(12),
                'requesting_person' => 'Engr. Manuel G. Torres',
                'office_college' => 'College of Industrial Technology',
                'destination' => 'Philippine Science High School, Clark Freeport Zone',
                'purpose' => 'Technical Skills Competition - Regional Level',
                'departure_date' => Carbon::today()->addDays(15),
                'departure_time' => '05:00',
                'return_date' => Carbon::today()->addDays(16),
                'num_passengers' => 10,
                'vehicle_status' => VehicleRequestStatus::APPROVED_DEAN->value,
                'passengers' => [
                    'John Carlo M. Reyes', 'Patricia Anne S. Cruz', 'Michael Angelo D. Santos',
                    'Kathleen Mae T. Garcia', 'Adrian Paul V. Lopez', 'Stephanie Joy R. Mendoza',
                    'Christian Mark A. Torres', 'Angelica Rose P. Aquino', 'Francis Neil B. Cruz',
                    'Michelle Anne L. Bautista'
                ],
            ],
            // 5. APPROVED BY VP (waiting for SUC)
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(15),
                'requesting_person' => 'Dr. Elena M. Bautista',
                'office_college' => 'College of Hospitality and Tourism Management',
                'destination' => 'Clark International Airport / Nayong Pilipino',
                'purpose' => 'Tourism Industry Exposure Tour for CHTM Students',
                'departure_date' => Carbon::today()->addDays(20),
                'departure_time' => '07:30',
                'return_date' => Carbon::today()->addDays(21),
                'num_passengers' => 25,
                'vehicle_status' => VehicleRequestStatus::APPROVED_VP->value,
                'passengers' => [
                    'Andrea Louise C. Santos', 'Ralph Lauren M. Cruz', 'Catherine Nicole V. Torres',
                    'John Robert A. Reyes', 'Maria Isabella P. Garcia', 'Miguel Antonio L. Santos',
                    'Patricia Anne M. Cruz', 'Ralph Kenneth D. Torres', 'Catherine Marie V. Reyes',
                    'John Michael P. Garcia', 'Miguel Carlos L. Santos', 'Patricia May M. Cruz',
                    'Ralph Christian D. Torres', 'Catherine Joy V. Reyes', 'John Patrick P. Garcia',
                    'Miguel Rafael L. Santos', 'Patricia Rose M. Cruz', 'Ralph Vincent D. Torres',
                    'Catherine Grace V. Reyes', 'John Carlo P. Garcia', 'Miguel Angelo L. Santos',
                    'Patricia Anne M. Cruz', 'Ralph Kenneth D. Torres', 'Catherine Marie V. Reyes',
                    'John Michael P. Garcia'
                ],
            ],
            // 6. PENDING FINAL MP APPROVAL (all approvals done, waiting for final MP)
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(20),
                'requesting_person' => 'Prof. Alexander R. Mendoza',
                'office_college' => 'College of Computing Studies',
                'destination' => 'UP Diliman, Quezon City',
                'purpose' => 'National IT Skills Olympiad - Student Delegation',
                'departure_date' => Carbon::today()->addDays(25),
                'departure_time' => '04:00',
                'return_date' => Carbon::today()->addDays(27),
                'num_passengers' => 12,
                'vehicle_status' => VehicleRequestStatus::PENDING_FINAL_MP->value,
                'passengers' => [
                    'Kevin J. Santos', 'Alyssa M. Cruz', 'Gabriel T. Torres',
                    'Samantha R. Reyes', 'Nathaniel P. Garcia', 'Victoria A. Santos',
                    'Christian L. Cruz', 'Gabrielle M. Torres', 'Nathaniel V. Reyes',
                    'Samantha P. Garcia', 'Christian D. Santos', 'Victoria T. Cruz'
                ],
            ],
            // 7. COMPLETED (fully approved, trip done)
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(30),
                'requesting_person' => 'Dr. Carmen L. Villanueva',
                'office_college' => 'College of Arts and Sciences',
                'destination' => 'National Museum of the Philippines, Manila',
                'purpose' => 'Educational Field Trip - Art Appreciation & Philippine History Classes',
                'departure_date' => Carbon::today()->subDays(15),
                'departure_time' => '06:00',
                'return_date' => Carbon::today()->subDays(14),
                'num_passengers' => 28,
                'vehicle_status' => VehicleRequestStatus::COMPLETED->value,
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
            // 8. NO VEHICLE AVAILABLE (rejected at Motor Pool)
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(10),
                'requesting_person' => 'Prof. Benjamin T. Cruz',
                'office_college' => 'Graduate School',
                'destination' => 'University of the Philippines, Los Baños',
                'purpose' => 'Research Collaboration Meeting with UPLB Faculty',
                'departure_date' => Carbon::today()->addDays(2),
                'departure_time' => '05:00',
                'return_date' => Carbon::today()->addDays(3),
                'num_passengers' => 4,
                'vehicle_status' => VehicleRequestStatus::NO_VEHICLE_AVAILABLE->value,
                'passengers' => [
                    'Dr. Maria Elena S. Cruz', 'Dr. Roberto A. Santos', 'Dr. Patricia M. Torres', 'Dr. Jonathan R. Reyes'
                ],
            ],
            // 9. REJECTED BY DEAN
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(25),
                'requesting_person' => 'Ms. Rosanna P. Garcia',
                'office_college' => 'Senior High School',
                'destination' => 'Enchanted Kingdom, Sta. Rosa, Laguna',
                'purpose' => 'SHS Year-End Educational Field Trip',
                'departure_date' => Carbon::today()->addDays(30),
                'departure_time' => '06:00',
                'return_date' => Carbon::today()->addDays(30),
                'num_passengers' => 50,
                'vehicle_status' => VehicleRequestStatus::REJECTED_DEAN->value,
                'passengers' => [
                    'Student 1', 'Student 2', 'Student 3', 'Student 4', 'Student 5',
                    'Student 6', 'Student 7', 'Student 8', 'Student 9', 'Student 10',
                    'Student 11', 'Student 12', 'Student 13', 'Student 14', 'Student 15',
                    'Student 16', 'Student 17', 'Student 18', 'Student 19', 'Student 20',
                    'Student 21', 'Student 22', 'Student 23', 'Student 24', 'Student 25',
                    'Student 26', 'Student 27', 'Student 28', 'Student 29', 'Student 30',
                    'Student 31', 'Student 32', 'Student 33', 'Student 34', 'Student 35',
                    'Student 36', 'Student 37', 'Student 38', 'Student 39', 'Student 40',
                    'Student 41', 'Student 42', 'Student 43', 'Student 44', 'Student 45',
                    'Student 46', 'Student 47', 'Student 48', 'Student 49', 'Student 50'
                ],
            ],
            // 10. CANCELLED BY USER
            [
                'user_ID' => $users->first()->id,
                'request_date' => Carbon::today()->subDays(40),
                'requesting_person' => 'Engr. Michael S. Torres',
                'office_college' => 'College of Engineering and Architecture',
                'destination' => 'Baguio City, Benguet',
                'purpose' => 'Site Inspection for Proposed Satellite Campus',
                'departure_date' => Carbon::today()->subDays(5),
                'departure_time' => '04:00',
                'return_date' => Carbon::today()->subDays(3),
                'num_passengers' => 6,
                'vehicle_status' => VehicleRequestStatus::CANCELLED->value,
                'passengers' => [
                    'Arch. Roberto M. Santos', 'Engr. Patricia L. Cruz', 'Engr. Michael A. Reyes',
                    'Engr. Catherine T. Torres', 'Engr. John P. Garcia', 'Engr. Miguel L. Santos'
                ],
            ],
        ];

        $createdCount = 0;

        foreach ($requests as $index => $reqData) {
            $passengers = $reqData['passengers'];
            unset($reqData['passengers']);

            // Create the vehicle request
            $request = VehicleRequest::create($reqData);

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

        $this->command->info("Seeded {$createdCount} vehicle requests with passengers and approval chains.");
    }

    protected function createApprovalChain(VehicleRequest $request): void
    {
        $status = $request->vehicle_status;
        $stages = [
            'Motor Pool' => [
                'Pending Motor Pool', 'Vehicle Available', 'No Vehicle Available',
            ],
            'Dean' => [
                'Pending Dean', 'Approved by Dean', 'Rejected by Dean',
            ],
            'Vice President' => [
                'Pending VP', 'Approved by VP', 'Rejected by VP',
            ],
            'SUC President' => [
                'Pending SUC', 'Approved by SUC', 'Rejected by SUC',
            ],
            'Motor Pool (Final)' => [
                'Pending Final MP Approval', 'Completed',
            ],
        ];

        $statusOrder = [
            VehicleRequestStatus::PENDING_MOTOR_POOL->value => 1,
            VehicleRequestStatus::VEHICLE_AVAILABLE->value => 2,
            VehicleRequestStatus::NO_VEHICLE_AVAILABLE->value => 2,
            VehicleRequestStatus::PENDING_DEAN->value => 3,
            VehicleRequestStatus::APPROVED_DEAN->value => 4,
            VehicleRequestStatus::REJECTED_DEAN->value => 4,
            VehicleRequestStatus::PENDING_VP->value => 5,
            VehicleRequestStatus::APPROVED_VP->value => 6,
            VehicleRequestStatus::REJECTED_VP->value => 6,
            VehicleRequestStatus::PENDING_SUC->value => 7,
            VehicleRequestStatus::APPROVED_SUC->value => 8,
            VehicleRequestStatus::REJECTED_SUC->value => 8,
            VehicleRequestStatus::PENDING_FINAL_MP->value => 9,
            VehicleRequestStatus::COMPLETED->value => 10,
            VehicleRequestStatus::CANCELLED->value => 0,
        ];

        $currentStageOrder = $statusOrder[$status] ?? 0;

        foreach ($stages as $role => $roleStatuses) {
            foreach ($roleStatuses as $s) {
                $stageOrder = $statusOrder[$s] ?? 0;

                $approvalStatus = 'Waiting';
                if ($stageOrder < $currentStageOrder) {
                    $approvalStatus = 'Approved';
                } elseif ($stageOrder === $currentStageOrder && in_array($status, ['Approved by Dean', 'Approved by VP', 'Approved by SUC', 'Completed', 'Vehicle Available'])) {
                    $approvalStatus = 'Approved';
                } elseif ($stageOrder === $currentStageOrder && in_array($status, ['Rejected by Dean', 'Rejected by VP', 'Rejected by SUC', 'No Vehicle Available'])) {
                    $approvalStatus = 'Rejected';
                } elseif ($stageOrder === $currentStageOrder && in_array($status, ['Pending Motor Pool', 'Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Final MP Approval'])) {
                    $approvalStatus = 'Pending';
                }

                \App\Models\Approval::create([
                    'approvable_type' => VehicleRequest::class,
                    'approvable_id' => $request->id,
                    'user_ID' => $approvalStatus !== 'Waiting' ? \App\Models\User::role($role)->inRandomOrder()->first()?->id : null,
                    'role' => $role,
                    'status' => $approvalStatus,
                    'approved_at' => $approvalStatus !== 'Waiting' ? Carbon::now()->subDays(rand(1, 5)) : null,
                ]);
            }
        }
    }
}
