<?php

namespace Database\Seeders;

use App\Models\EmpEmploymentType;
use App\Models\LeaveRatio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveRatioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  1,
            'leave_credit'          =>  0.5,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  2,
            'leave_credit'          =>  1,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  3,
            'leave_credit'          =>  0.5,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  4,
            'leave_credit'          =>  1,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  5,
            'leave_credit'          =>  2,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  6,
            'leave_credit'          =>  0.5,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  7,
            'leave_credit'          =>  2,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  2,
            'leave_type_id'         =>  8,
            'leave_credit'          =>  2,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  4,
            'leave_type_id'         =>  1,
            'leave_credit'          =>  0.5,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);
        LeaveRatio::create([
            'employment_type_id'    =>  4,
            'leave_type_id'         =>  2,
            'leave_credit'          =>  2,
            'frequency'             =>  'monthly',
            'status'                =>  1
        ]);


        for ($i = 1; $i <= 7; $i++) {            
            EmpEmploymentType::create([
                'user_id'               => $i,
                'employment_type_id'    =>  2,
            ]);
        }
    }
}
