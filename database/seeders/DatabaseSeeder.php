<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DepartmentTableSeeder::class);
        $this->call(ActivityTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TechnologyTableSeeder::class);
        $this->call(LeaveTypeSeeder::class);
        $this->call(EmploymentTypeSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(ShifttimeruleSeeder::class);
        $this->call(LeaveRatioSeeder::class);
        $this->call(HrAnnouncementTableSeeder::class);
        $this->call(MotivationalQuoteTableSeeder::class);
        $this->call(HolidayTableSeeder::class);
        $this->call(ClientTableSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(WorklogTableSeeder::class);
    }
}
