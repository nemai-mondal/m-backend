<?php

namespace Database\Seeders;

use App\Models\EmploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employementTypes = [
            0 => [
                'name'              =>  'probation',
                'duration'          =>  2,
                'duration_type'     =>  'month'
            ],
            1 => [
                'name'              =>  'confirmed',
                'duration'          =>  0,
                'duration_type'     =>  'unlimited'
            ],
            2 => [
                'name'              =>  'contract',
                'duration'          =>  0,
                'duration_type'     =>  'unlimited'
            ],
            3 => [
                'name'              =>  'notice',
                'duration'          =>  2,
                'duration_type'     =>  'month'
            ],
        ];

        foreach($employementTypes as $type) {

            EmploymentType::create([
                'name'          => $type['name'],
                'duration'      => $type['duration'],
                'duration_type' => $type['duration_type'],
                'status'        => 1, 
            ]);
        }
    }
}
