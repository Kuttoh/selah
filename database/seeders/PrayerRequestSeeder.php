<?php

namespace Database\Seeders;

use App\Models\PrayerRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrayerRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prayers = [
            'For healing and strength during difficult times',
            'For wisdom in making important decisions',
            'For peace and calm in the midst of chaos',
            'For protection and safety for my family',
            'For guidance in my career and life path',
            'For healing from past wounds and trauma',
            'For healthy relationships with loved ones',
            'For financial stability and abundance',
            'For mental health and emotional well-being',
            'For patience and grace in challenging situations',
            'For the health of a sick friend or family member',
            'For courage to face upcoming challenges',
            'For forgiveness and reconciliation',
            'For clarity and purpose in life',
            'For strength to overcome my fears',
            'For joy and happiness to return',
            'For guidance in spiritual growth',
            'For unity and peace in my community',
            'For safe travels for loved ones',
            'For hope and encouragement',
            'For success in upcoming projects',
            'For gratitude and contentment',
            'For wisdom in parenting and family',
            'For healing in broken relationships',
            'For strength and resilience',
        ];

        $names = [
            'Sarah',
            'John',
            'Maria',
            'David',
            'Emma',
            'James',
            'Lisa',
            'Michael',
            'Jennifer',
            'Robert',
            null,
            null,
            null,
        ];

        $users = User::all();

        foreach ($prayers as $index => $prayer) {
            $isPrayedFor = fake()->boolean(40); // 40% chance of being prayed for
            $prayedBy = null;

            if ($isPrayedFor && $users->isNotEmpty()) {
                $prayedBy = $users->random()->id;
            }

            PrayerRequest::create([
                'prayer' => $prayer,
                'name' => fake()->randomElement($names),
                'is_prayed_for' => $isPrayedFor,
                'prayed_at' => $isPrayedFor ? fake()->dateTimeBetween('-30 days') : null,
                'prayed_by' => $prayedBy,
            ]);
        }
    }
}
