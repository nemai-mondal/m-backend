<?php

namespace Database\Seeders;

use App\Models\HR;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HrAnnouncementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title'             =>  'New Year Celebration',
                'description'       =>  'An event is organized to celebrate the new year in our office.',
                'user_id'           =>  1,
                'department_id'     =>  0,
                'event_date'        =>  Carbon::create(2024, 12, 31),
                'event_start_time'  =>  Carbon::createFromTime(17, 30, 0),
                'event_end_time'    =>  Carbon::createFromTime(19, 0, 0),
            ],
            [
                'title'             =>  'Republic Day Celebration',
                'description'       =>  'An event is organized to celebrate the republic day in our office.',
                'user_id'           =>  1,
                'department_id'     =>  0,
                'event_date'        =>  Carbon::create(2024, 1, 25),
                'event_start_time'  =>  Carbon::createFromTime(17, 30, 0),
                'event_end_time'    =>  Carbon::createFromTime(19, 0, 0),
            ],
            [
                'title'             =>  'Quiz Game',
                'description'       =>  'Get ready to challenge your self.',
                'user_id'           =>  1,
                'department_id'     =>  0,
                'event_date'        =>  Carbon::create(2024, 4, 23),
                'event_start_time'  =>  Carbon::createFromTime(11, 0, 0),
                'event_end_time'    =>  Carbon::createFromTime(12, 0, 0),
            ],
        ];

        /**
         * Commented out the code because these are not relevent announcements
         * It was written for only testing purpose
         */
        // foreach($announcements as $announcement) {

        //     HR::create([
        //         'title'             => $announcement['title'],
        //         'description'       => $announcement['description'],
        //         'event_date'        => $announcement['event_date'],
        //         'event_start_time'  => $announcement['event_start_time'],
        //         'event_end_time'    => $announcement['event_end_time'],
        //         'department_id'     => $announcement['department_id'],
        //         'user_id'           => 1,
        //     ]);
        // }
    }
}
