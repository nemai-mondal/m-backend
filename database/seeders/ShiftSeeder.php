<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::create([
            'name'          =>  'India',
            'shift_start'   =>  '10:00:00',
            'shift_end'     =>  '19:00:00',
            'timezone'      =>  null,
            'status'        =>  1
        ]);
        Shift::create([
            'name'          =>  'US',
            'shift_start'   =>  '22:00:00',
            'shift_end'     =>  '07:00:00',
            'timezone'      =>  null,
            'status'        =>  1
        ]);
        Shift::create([
            'name'          =>  'Australia',
            'shift_start'   =>  '06:00:00',
            'shift_end'     =>  '15:00:00',
            'timezone'      =>  null,
            'status'        =>  1
        ]);
        Shift::create([
            'name'          =>  'Russia',
            'shift_start'   =>  '12:00:00',
            'shift_end'     =>  '21:00:00',
            'timezone'      =>  null,
            'status'        =>  1
        ]);
        Shift::create([
            'name'          =>  'Germany',
            'shift_start'   =>  '14:00:00',
            'shift_end'     =>  '23:00:00',
            'timezone'      =>  null,
            'status'        =>  1
        ]);
    }
}
