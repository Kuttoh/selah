<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'Men', 'description' => 'Men\'s fellowship group', 'display_order' => 1],
            ['name' => 'Women', 'description' => 'Women\'s fellowship group', 'display_order' => 2],
            ['name' => 'Youth', 'description' => 'Youth ministry group', 'display_order' => 3],
            ['name' => 'Mothers Union', 'description' => 'Mothers Union group', 'display_order' => 4],
            ['name' => 'Children', 'description' => 'Children\'s ministry group', 'display_order' => 5],
        ];

        foreach ($groups as $group) {
            Group::create([
                'name' => $group['name'],
                'description' => $group['description'],
                'active' => true,
                'display_order' => $group['display_order'],
            ]);
        }
    }
}
