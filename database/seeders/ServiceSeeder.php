<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Main 8am', 'description' => 'Main morning service at 8:00 AM', 'display_order' => 1],
            ['name' => 'Youth 9.30am', 'description' => 'Youth service at 9:30 AM', 'display_order' => 2],
            ['name' => 'Vuka 11.30am', 'description' => 'Vuka service at 11:30 AM', 'display_order' => 3],
            ['name' => 'Evening Service', 'description' => 'Evening worship service', 'display_order' => 4],
        ];

        foreach ($services as $service) {
            Service::create([
                'name' => $service['name'],
                'description' => $service['description'],
                'active' => true,
                'display_order' => $service['display_order'],
            ]);
        }
    }
}
