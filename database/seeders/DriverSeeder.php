<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = [
            [
                'full_name' => 'Roberto M. Santos',
                'license_number' => 'N01-85-123456',
                'license_expiry' => '2028-03-15',
                'contact' => '0917-123-4567',
                'position' => 'Regular Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Fernando L. Cruz',
                'license_number' => 'N02-88-234567',
                'license_expiry' => '2027-11-20',
                'contact' => '0918-234-5678',
                'position' => 'Senior Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Ramon D. Torres',
                'license_number' => 'N03-90-345678',
                'license_expiry' => '2029-05-10',
                'contact' => '0919-345-6789',
                'position' => 'Regular Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Eduardo V. Reyes',
                'license_number' => 'N04-92-456789',
                'license_expiry' => '2026-08-22',
                'contact' => '0920-456-7890',
                'position' => 'Regular Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Antonio G. Mendoza',
                'license_number' => 'N05-87-567890',
                'license_expiry' => '2028-12-05',
                'contact' => '0921-567-8901',
                'position' => 'Senior Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Jose R. Garcia',
                'license_number' => 'N06-89-678901',
                'license_expiry' => '2027-06-18',
                'contact' => '0922-678-9012',
                'position' => 'Regular Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Manuel P. Lopez',
                'license_number' => 'N07-91-789012',
                'license_expiry' => '2029-02-28',
                'contact' => '0923-789-0123',
                'position' => 'Regular Driver',
                'status' => 'On Leave',
            ],
            [
                'full_name' => 'Ricardo S. Herrera',
                'license_number' => 'N08-86-890123',
                'license_expiry' => '2026-11-30',
                'contact' => '0924-890-1234',
                'position' => 'Senior Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Pedro A. Jimenez',
                'license_number' => 'N09-93-901234',
                'license_expiry' => '2028-09-12',
                'contact' => '0925-901-2345',
                'position' => 'Regular Driver',
                'status' => 'Available',
            ],
            [
                'full_name' => 'Carlos M. Rivera',
                'license_number' => 'N10-88-012345',
                'license_expiry' => '2027-03-25',
                'contact' => '0926-012-3456',
                'position' => 'Regular Driver',
                'status' => 'Available',
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::firstOrCreate(
                ['license_number' => $driver['license_number']],
                $driver
            );
        }

        $this->command->info('Seeded ' . count($drivers) . ' drivers.');
    }
}