<?php

namespace Database\Seeders;

use App\Models\Worklog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorklogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * It was created for testing only
         */
        // Worklog::create([
        //     "date"  =>  date('Y-m-d'),
        //     "user_id"   =>  1,
        //     "task_url"  =>  'MAG-21',
        //     "client_id" =>  1,
        //     "project_id"    =>  1,
        //     "time_spent"    =>  "1:11:00",
        //     "activity_id"   =>  1,
        //     "description"   =>  "",
        // ]);
        // Worklog::create([
        //     "date"  =>  date('Y-m-d'),
        //     "user_id"   =>  2,
        //     "task_url"  =>  'MAG-22',
        //     "client_id" =>  2,
        //     "project_id"    =>  2,
        //     "time_spent"    =>  '2:22:00',
        //     "activity_id"   =>  2,
        //     "description"   =>  "",
        // ]);
        // Worklog::create([
        //     "date"  =>  date('Y-m-d'),
        //     "user_id"   =>  3,
        //     "task_url"  =>  'MAG-23',
        //     "client_id" =>  3,
        //     "project_id"    =>  3,
        //     "time_spent"    =>  '3:33:00',
        //     "activity_id"   =>  3,
        //     "description"   =>  "",
        // ]);
        // Worklog::create([
        //     "date"  =>  date('Y-m-d'),
        //     "user_id"   =>  4,
        //     "task_url"  =>  'MAG-24',
        //     "client_id" =>  4,
        //     "project_id"    =>  4,
        //     "time_spent"    =>  '4:44:00',
        //     "activity_id"   =>  4,
        //     "description"   =>  "",
        // ]);
    }
}
