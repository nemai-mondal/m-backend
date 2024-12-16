<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'name'          => 'Laravel Developer',
                'department_id' => 1,
            ],
            [
                'name'          => 'Sr. Software Developer',
                'department_id' => 1,
            ],
            [
                'name'          => 'Frontend Developer',
                'department_id' => 1,
            ],
            [
                'name'          => 'Backend Developer',
                'department_id' => 1,
            ],
            [
                'name'          => 'Full Stack Developer',
                'department_id' => 1,
            ],
            [
                'name'          => 'UI/UX Designer',
                'department_id' => 7,
            ],
            [
                'name'          => 'DevOps Engineer',
                'department_id' => 1,
            ],
            [
                'name'          => 'Data Scientist',
                'department_id' => 1,
            ],
            [
                'name'          => 'Project Manager',
                'department_id' => 1,
            ],
            [
                'name'          => 'Quality Assurance Engineer',
                'department_id' => 8,
            ],
            [
                'name'          => 'HR Associate',
                'department_id' => 4,
            ],
            [
                'name'          => 'Senior HR',
                'department_id' => 4,
            ],
            [
                'name'          => 'Senior Accountant',
                'department_id' => 5,
            ],
        ];
        
        foreach ($designations as $type) {
            Designation::create([
                'name'          => $type['name'],
                'status'        => 1,
                'department_id' => $type['department_id'],
            ]);
        }
    }
}
