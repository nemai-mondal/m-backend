<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShiftRule;
use Illuminate\Support\Facades\DB;

class ShifttimeruleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shift_rule = [
            [
                'name' => 'Half Day',
            ],
            [
                'name' => 'Buffer Time',
            ],
            [
                'name' => 'No Work Below Hours',
            ],
        ];

        foreach ($shift_rule as $type) {
            ShiftRule::create([
                'name' => $type['name'],
                // 'time' => 1,
            ]);
        }
    }
}
