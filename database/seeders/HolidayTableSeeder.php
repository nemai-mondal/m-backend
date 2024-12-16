<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            [
                'holiday_name'  =>  'New Year',
                'date_from'     =>  Carbon::create(2024, 1, 01),
                'date_to'       =>  Carbon::create(2024, 1, 01),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Republic Day',
                'date_from'     =>  Carbon::create(2024, 1, 26),
                'date_to'       =>  Carbon::create(2024, 1, 26),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Dol Jatra',
                'date_from'     =>  Carbon::create(2024, 3, 25),
                'date_to'       =>  Carbon::create(2024, 3, 25),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Eid-Ul-Fitr',
                'date_from'     =>  Carbon::create(2024, 4, 10),
                'date_to'       =>  Carbon::create(2024, 4, 10),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Labour Day',
                'date_from'     =>  Carbon::create(2024, 5, 01),
                'date_to'       =>  Carbon::create(2024, 5, 01),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Independence Day',
                'date_from'     =>  Carbon::create(2024, 8, 15),
                'date_to'       =>  Carbon::create(2024, 8, 15),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Gandhi Jayanti',
                'date_from'     =>  Carbon::create(2024, 10, 02),
                'date_to'       =>  Carbon::create(2024, 10, 02),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Durga Puja',
                'date_from'     =>  Carbon::create(2024, 10, 9),
                'date_to'       =>  Carbon::create(2024, 10, 9),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Durga Puja',
                'date_from'     =>  Carbon::create(2024, 10, 10),
                'date_to'       =>  Carbon::create(2024, 10, 10),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Durga Puja',
                'date_from'     =>  Carbon::create(2024, 10, 11),
                'date_to'       =>  Carbon::create(2024, 10, 11),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Dipawali',
                'date_from'     =>  Carbon::create(2024, 11, 1),
                'date_to'       =>  Carbon::create(2024, 11, 1),
                'days'          =>  1,
            ],
            [
                'holiday_name'  =>  'Christmas Day',
                'date_from'     =>  Carbon::create(2024, 12, 25),
                'date_to'       =>  Carbon::create(2024, 12, 25),
                'days'          =>  1,
            ],
        ];

        foreach($holidays as $holiday) {

            $carbonDate = Carbon::parse($holiday['date_from']);
            // $dayOfWeek  = $carbonDate->format('l');

            Holiday::create([
                'holiday_name'  =>  $holiday['holiday_name'],
                'date_from'     =>  $holiday['date_from'],
                'date_to'       =>  $holiday['date_to'],
                'days'          =>  $holiday['days'],
                // 'day'           =>  $dayOfWeek,
                'status'        =>  1,
            ]);
        }
    }
}
