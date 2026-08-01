<?php

namespace Database\Seeders;

use App\Models\TravelRequest;
use App\Models\User;
use App\Enums\TravelRequestStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TravelRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'User');
        })->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users with "User" role found. Skipping TravelRequestSeeder.');
            return;
        }

        $requests = [
            // 1. PENDING DEAN (just submitted)
            [
                'user' => $users->first(),
                'personnel_name' => 'Prof. Jose R. Aquino',
                'official_station' => 'College of Arts and Sciences',
                'destination' => 'Baguio Convention Center, Baguio City',
                'purpose' => 'Attendance to National Conference on Philippine Literature and Languages',
                'inclusive_date' => Carbon::today()->addDays(10),
                'requesting_for' => 'Cash Advance',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::PENDING_DEAN->value,
            ],
            // 2. APPROVED BY DEAN (waiting for VP)
            [
                'user' => $users->first(),
                'personnel_name' => 'Dr. Lourdes M. Santos',
                'official_station' => 'College of Education',
                'destination' => 'DepEd Central Office, Meralco Ave, Pasig City',
                'purpose' => 'Consultation Meeting on MATATAG Curriculum Implementation',
                'inclusive_date' => Carbon::today()->addDays(15),
                'requesting_for' => 'Reimbursement',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::APPROVED_DEAN->value,
            ],
            // 3. APPROVED BY VP (waiting for SUC)
            [
                'user' => $users->first(),
                'personnel_name' => 'Engr. Ricardo T. Cruz',
                'official_station' => 'College of Engineering and Architecture',
                'destination' => 'Philippine International Convention Center, Manila',
                'purpose' => '2025 Philippine Engineering Summit - Paper Presenter',
                'inclusive_date' => Carbon::today()->addDays(20),
                'requesting_for' => 'Cash Advance',
                'vehicle_request' => 'No',
                'vehicle_status' => TravelRequestStatus::APPROVED_VP->value,
            ],
            // 4. APPROVED BY SUC (waiting for Motor Pool)
            [
                'user' => $users->first(),
                'personnel_name' => 'Prof. Carmela V. Reyes',
                'official_station' => 'College of Hospitality and Tourism Management',
                'destination' => 'Boracay Island, Aklan',
                'purpose' => 'Site Inspection for Proposed CHTM Educational Tourism Program',
                'inclusive_date' => Carbon::today()->addDays(25),
                'requesting_for' => 'Reimbursement',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::APPROVED_SUC->value,
            ],
            // 5. PENDING MOTOR POOL (all approvals done, waiting for vehicle assignment)
            [
                'user' => $users->first(),
                'personnel_name' => 'Dr. Antonio P. Garcia',
                'official_station' => 'College of Business Studies',
                'destination' => 'ASEAN Business Conference, Shangri-La Hotel, Makati',
                'purpose' => 'Keynote Speaker - ASEAN Economic Integration Forum',
                'inclusive_date' => Carbon::today()->addDays(30),
                'requesting_for' => 'Cash Advance',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::PENDING_MOTOR_POOL->value,
            ],
            // 5. VEHICLE AVAILABLE (Motor Pool assigned vehicle)
            [
                'user' => $users->first(),
                'personnel_name' => 'Prof. Maria Elena T. Cruz',
                'official_station' => 'College of Computing Studies',
                'destination' => 'SMX Convention Center, MOA, Pasay City',
                'purpose' => 'National IT Education Conference - Panel Discussant',
                'inclusive_date' => Carbon::today()->addDays(5),
                'requesting_for' => 'Cash Advance',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::VEHICLE_AVAILABLE->value,
            ],
            // 6. COMPLETED (fully approved, trip done)
            [
                'user' => $users->first(),
                'personnel_name' => 'Dr. Patricia M. Lim',
                'official_station' => 'Graduate School',
                'destination' => 'University of the Philippines Diliman, Quezon City',
                'purpose' => 'Thesis Defense - PhD in Educational Management Student',
                'inclusive_date' => Carbon::today()->subDays(10),
                'requesting_for' => 'Reimbursement',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::COMPLETED->value,
            ],
            // 7. NO VEHICLE AVAILABLE
            [
                'user' => $users->first(),
                'personnel_name' => 'Engr. Roberto S. Torres',
                'official_station' => 'College of Industrial Technology',
                'destination' => 'Technological University of the Philippines, Manila',
                'purpose' => 'Accreditation Preparation Workshop - BIT Program',
                'inclusive_date' => Carbon::today()->addDays(3),
                'requesting_for' => 'N/A',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::NO_VEHICLE_AVAILABLE->value,
            ],
            // 8. REJECTED BY DEAN
            [
                'user' => $users->first(),
                'personnel_name' => 'Ms. Rosanna P. Dela Cruz',
                'official_station' => 'Senior High School',
                'destination' => 'Enchanted Kingdom, Sta. Rosa, Laguna',
                'purpose' => 'SHS Educational Field Trip - Recreational Activity',
                'inclusive_date' => Carbon::today()->addDays(45),
                'requesting_for' => 'N/A',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::REJECTED_DEAN->value,
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
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::REJECTED_VP->value,
            ],
            // 10. CANCELLED
            [
                'user' => $users->first(),
                'personnel_name' => 'Dr. Carmen L. Villanueva',
                'official_station' => 'College of Arts and Sciences',
                'destination' => 'National Museum of the Philippines, Manila',
                'purpose' => 'Educational Field Trip - Art Appreciation Class (Cancelled - Typhoon)',
                'inclusive_date' => Carbon::today()->subDays(5),
                'requesting_for' => 'Reimbursement',
                'vehicle_request' => 'Yes',
                'vehicle_status' => TravelRequestStatus::CANCELLED->value,
            ],
        ];

        $createdCount = 0;

        foreach ($requests as $reqData) {
            $user = $reqData['user'];
            unset($reqData['user']);

            $request = TravelRequest::create(array_merge($reqData, [
                'user_ID' => $user->id,
            ]));

            // Create approval chain based on status
            $this->createApprovalChain($request);

            $createdCount++;
        }

        $this->command->info("Seeded {$createdCount} travel requests with approval chains.");
    }

    protected function createApprovalChain(TravelRequest $request): void
    {
        $status = $request->vehicle_status;

        $stages = [
            'Dean' => [
                TravelRequestStatus::PENDING_DEAN->value,
                TravelRequestStatus::APPROVED_DEAN->value,
                TravelRequestStatus::REJECTED_DEAN->value,
            ],
            'Vice President' => [
                TravelRequestStatus::PENDING_VP->value,
                TravelRequestStatus::APPROVED_VP->value,
                TravelRequestStatus::REJECTED_VP->value,
            ],
            'SUC President' => [
                TravelRequestStatus::PENDING_SUC->value,
                TravelRequestStatus::APPROVED_SUC->value,
                TravelRequestStatus::REJECTED_SUC->value,
            ],
            'Motor Pool' => [
                TravelRequestStatus::PENDING_MOTOR_POOL->value,
                TravelRequestStatus::VEHICLE_AVAILABLE->value,
                TravelRequestStatus::NO_VEHICLE_AVAILABLE->value,
            ],
        ];

        $statusOrder = [
            TravelRequestStatus::PENDING_DEAN->value => 1,
            TravelRequestStatus::APPROVED_DEAN->value => 2,
            TravelRequestStatus::REJECTED_DEAN->value => 2,
            TravelRequestStatus::PENDING_VP->value => 3,
            TravelRequestStatus::APPROVED_VP->value => 4,
            TravelRequestStatus::REJECTED_VP->value => 4,
            TravelRequestStatus::PENDING_SUC->value => 5,
            TravelRequestStatus::APPROVED_SUC->value => 6,
            TravelRequestStatus::REJECTED_SUC->value => 6,
            TravelRequestStatus::PENDING_MOTOR_POOL->value => 7,
            TravelRequestStatus::VEHICLE_AVAILABLE->value => 8,
            TravelRequestStatus::NO_VEHICLE_AVAILABLE->value => 8,
            TravelRequestStatus::COMPLETED->value => 9,
            TravelRequestStatus::CANCELLED->value => 0,
        ];

        $currentStageOrder = $statusOrder[$status] ?? 0;

        foreach ($stages as $role => $roleStatuses) {
            foreach ($roleStatuses as $s) {
                $stageOrder = $statusOrder[$s] ?? 0;

                $approvalStatus = 'Waiting';
                if ($stageOrder < $currentStageOrder) {
                    $approvalStatus = 'Approved';
                } elseif ($stageOrder === $currentStageOrder && in_array($status, [
                    TravelRequestStatus::APPROVED_DEAN->value,
                    TravelRequestStatus::APPROVED_VP->value,
                    TravelRequestStatus::APPROVED_SUC->value,
                    TravelRequestStatus::VEHICLE_AVAILABLE->value,
                    TravelRequestStatus::COMPLETED->value,
                ])) {
                    $approvalStatus = 'Approved';
                } elseif ($stageOrder === $currentStageOrder && in_array($status, [
                    TravelRequestStatus::REJECTED_DEAN->value,
                    TravelRequestStatus::REJECTED_VP->value,
                    TravelRequestStatus::REJECTED_SUC->value,
                    TravelRequestStatus::NO_VEHICLE_AVAILABLE->value,
                ])) {
                    $approvalStatus = 'Rejected';
                } elseif ($stageOrder === $currentStageOrder && in_array($status, [
                    TravelRequestStatus::PENDING_DEAN->value,
                    TravelRequestStatus::PENDING_VP->value,
                    TravelRequestStatus::PENDING_SUC->value,
                    TravelRequestStatus::PENDING_MOTOR_POOL->value,
                ])) {
                    $approvalStatus = 'Pending';
                }

                \App\Models\Approval::create([
                    'approvable_type' => TravelRequest::class,
                    'approvable_id' => $request->id,
                    'user_ID' => $approvalStatus !== 'Waiting'
                        ? \App\Models\User::role($role)->inRandomOrder()->first()?->id
                        : null,
                    'role' => $role,
                    'status' => $approvalStatus,
                    'approved_at' => $approvalStatus !== 'Waiting'
                        ? Carbon::now()->subDays(rand(1, 5))
                        : null,
                ]);
            }
        }
    }
}
