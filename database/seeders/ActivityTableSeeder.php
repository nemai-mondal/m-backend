<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'name'          => 'Backend Development',
                'department_id' => 1,
            ],
            [
                'name'          => 'API',
                'department_id' => 1,
            ],
            [
                'name'          => 'Games & Activities',
                'department_id' => 4,
            ],
            [
                'name'          => 'HR Meeting',
                'department_id' => 4,
            ],
            [
                'name'          => 'Project Discussion',
                'department_id' => 1,
            ],
            [
                'name'          => 'Front end development',
                'department_id' => 1,
            ],
            [
                'name'          => 'Candidate Calling',
                'department_id' => 4,
            ],
            [
                'name'          => 'Candidate Onboarding',
                'department_id' => 4,
            ],
            [
                'name'          => 'Product Testing',
                'department_id' => 8,
            ],
            [
                'name'          => 'iOS Development',
                'department_id' => 1,
            ],
            [
                'name'          => 'Social Media Post',
                'department_id' => 2,
            ],
            [
                'name'          => 'Content Writing',
                'department_id' => 2,
            ],
        ];

        foreach ($designations as $type) {
            Activity::create([
                'name'          => $type['name'],
                'status'        => 1,
                'department_id' => $type['department_id'],
            ]);
        }
    }
}
