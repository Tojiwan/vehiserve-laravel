<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'vehicle_name' => 'Toyota Hiace Commuter',
                'plate_number' => 'SKP-1001',
                'model' => 'Toyota Hiace',
                'color' => 'White',
                'capacity' => 14,
                'status' => 'Available',
                'type' => 'Van',
                'description' => 'Standard commuter van for personnel transport',
                'date_acquired' => '2022-03-15',
                'date_last_maintained' => '2025-11-20',
            ],
            [
                'vehicle_name' => 'Toyota Coaster',
                'plate_number' => 'SKP-1002',
                'model' => 'Toyota Coaster',
                'color' => 'White/Blue',
                'capacity' => 28,
                'status' => 'Available',
                'type' => 'Bus',
                'description' => 'Medium bus for group transport',
                'date_acquired' => '2021-08-20',
                'date_last_maintained' => '2025-10-15',
            ],
            [
                'vehicle_name' => 'Hyundai H100',
                'plate_number' => 'SKP-1003',
                'model' => 'Hyundai H100',
                'color' => 'White',
                'capacity' => 3,
                'status' => 'Available',
                'type' => 'Truck',
                'description' => 'Light truck for equipment/material transport',
                'date_acquired' => '2023-01-10',
                'date_last_maintained' => '2025-11-05',
            ],
            [
                'vehicle_name' => 'Toyota Innova',
                'plate_number' => 'SKP-1004',
                'model' => 'Toyota Innova 2.8 G Diesel',
                'color' => 'Silver',
                'capacity' => 7,
                'status' => 'Available',
                'type' => 'Car',
                'description' => 'MPV for small group executive transport',
                'date_acquired' => '2022-11-05',
                'date_last_maintained' => '2025-11-18',
            ],
            [
                'vehicle_name' => 'Mitsubishi Fuso Canter',
                'plate_number' => 'SKP-1005',
                'model' => 'Mitsubishi Fuso Canter FB',
                'color' => 'White',
                'capacity' => 3,
                'status' => 'Maintenance',
                'type' => 'Truck',
                'description' => 'Medium truck for heavy equipment transport',
                'date_acquired' => '2020-06-12',
                'date_last_maintained' => '2025-10-01',
            ],
            [
                'vehicle_name' => 'Hyundai County',
                'plate_number' => 'SKP-1006',
                'model' => 'Hyundai County',
                'color' => 'White/Green',
                'capacity' => 25,
                'status' => 'On Trip',
                'type' => 'Bus',
                'description' => 'Medium bus for student/employee transport',
                'date_acquired' => '2021-04-30',
                'date_last_maintained' => '2025-10-28',
            ],
            [
                'vehicle_name' => 'Toyota HiAce GL Grandia',
                'plate_number' => 'SKP-1007',
                'model' => 'Toyota HiAce GL Grandia',
                'color' => 'Pearl White',
                'capacity' => 10,
                'status' => 'Available',
                'type' => 'Van',
                'description' => 'Premium van for executive transport',
                'date_acquired' => '2023-06-15',
                'date_last_maintained' => '2025-11-22',
            ],
            [
                'vehicle_name' => 'Isuzu N-Series',
                'plate_number' => 'SKP-1008',
                'model' => 'Isuzu NQR71',
                'color' => 'White',
                'capacity' => 3,
                'status' => 'Available',
                'type' => 'Truck',
                'description' => 'Light truck for supplies delivery',
                'date_acquired' => '2022-09-20',
                'date_last_maintained' => '2025-11-10',
            ],
            [
                'vehicle_name' => 'Toyota Coaster Deluxe',
                'plate_number' => 'SKP-1009',
                'model' => 'Toyota Coaster Deluxe',
                'color' => 'White/Red',
                'capacity' => 30,
                'status' => 'Available',
                'type' => 'Bus',
                'description' => 'Large bus for mass transport',
                'date_acquired' => '2019-11-11',
                'date_last_maintained' => '2025-09-15',
            ],
            [
                'vehicle_name' => 'Toyota Vios',
                'plate_number' => 'SKP-1010',
                'model' => 'Toyota Vios 1.5 G',
                'color' => 'Gray Metallic',
                'capacity' => 4,
                'status' => 'Available',
                'type' => 'Car',
                'description' => 'Sedan for official business trips',
                'date_acquired' => '2024-02-14',
                'date_last_maintained' => '2025-11-25',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::firstOrCreate(
                ['plate_number' => $vehicle['plate_number']],
                $vehicle
            );
        }

        $this->command->info('Seeded ' . count($vehicles) . ' vehicles.');
    }
}