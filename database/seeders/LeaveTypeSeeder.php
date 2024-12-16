<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            0 => [
                'name'  =>  'Casual Leave',
                'abbreviation'  =>  'CL',
                'comment'       =>  'Only confirmed employees are eligible for this leave.'
            ],
            1 => [
                'name'  =>  'Sick Leave',
                'abbreviation'  =>  'SL',
                'comment'       =>  'Only confirmed employees are eligible for this leave.'
            ],
            2 => [
                'name'  =>  'Privilege Leave',
                'abbreviation'  =>  'PL',
                'comment'       =>  'Only confirmed employees are eligible for this leave.'
            ],
            3 => [
                'name'  =>  'Compensatory Off',
                'abbreviation'  =>  'CO',
                'comment'       =>  'Only confirmed employees are eligible for this leave.'
            ],
            4 => [
                'name'  =>  'Maternity Leave',
                'abbreviation'  =>  'MRL',
                'comment'       =>  'Only confirmed employees are eligible for this leave.'
            ],
            5 => [
                'name'  =>  'Paternity Leave',
                'abbreviation'  =>  'PRL',
                'comment'       =>  'Only confirmed employees are eligible for this leave.'
            ],
            6 => [
                'name'  =>  'Marriage Leave',
                'abbreviation'  =>  'ML',
                'comment'       =>  'Only confirmed employees are eligible for this leave.'
            ],
            7 => [
                'name'  =>  'Leave Withought Pay',
                'abbreviation'  =>  'LWP',
                'comment'       =>  'All employees are eligible for this leave.'
            ],
        ];

        foreach($leaveTypes as $type) {

            LeaveType::create([
                'name'          => $type['name'],
                'abbreviation'  => $type['abbreviation'],
                'comment'       => $type['comment'],
                'status'        => 1, 
            ]);
        }
    }
}
