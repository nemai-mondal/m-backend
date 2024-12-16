<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        $departments = [
            [
                'name' => 'Development',
            ],
            [
                'name' => 'Marketing',
            ],
            [
                'name' => 'Sales',
            ],
            [
                'name' => 'Human Resources',
            ],
            [
                'name' => 'Finance',
            ],
            [
                'name' => 'Customer Support',
            ],
            [
                'name' => 'Design',
            ],
            [
                'name' => 'Quality Assurance',
            ],
        ];

        foreach ($departments as $type) {
            Department::create([
                'name' => $type['name'],
                'status' => 1,
            ]);
        }
    }
}
