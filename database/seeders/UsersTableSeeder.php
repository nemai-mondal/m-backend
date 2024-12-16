<?php

namespace Database\Seeders;

use App\Models\EmpEmploymentType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate(
            ['email'    => 'developers@magicminds.io'],
            [
                'honorific'             => 'Mr.',
                'first_name'            => 'MMT',
                'middle_name'           => '',
                'last_name'             => 'Admin',
                'employee_id'           => 'MMT001',
                'status'                => 1,
                'password'              => Hash::make('Passw0rd@123'),
                'password_updated'      => 1,
                'onboard_confirmed'     => 1,
            ]
        );

        $user->assignRole('super_admin');

        $user = User::updateOrCreate(
            ['email'    => 'b.diptendu@magicminds.io'],
            [
                'honorific'             => 'Mr.',
                'first_name'            => 'Diptendu',
                'middle_name'           => '',
                'last_name'             => 'Barman',
                'employee_id'           => 'MMT002',
                'status'                => 1,
                'password'              => Hash::make('Passw0rd@123'),
                'password_updated'      => 1,
                'onboard_confirmed'     => 1,
            ]
        );

        $user->assignRole('admin');

        $user = User::updateOrCreate(
            ['email'    => 'b.pritam@magicminds.io'],
            [
                'honorific'             => 'Mr.',
                'first_name'            => 'Pritam',
                'middle_name'           => '',
                'last_name'             => 'Bhar',
                'employee_id'           => 'MMT003',
                'status'                => 1,
                'password'              => Hash::make('Passw0rd@123'),
                'password_updated'      => 1,
                'onboard_confirmed'     => 1,
            ]
        );

        $user->assignRole('admin');

        $user = User::updateOrCreate(
            ['email'    => 's.sushobhan@magicminds.io'],
            [
                'honorific'             => 'Mr.',
                'first_name'            => 'Sushobhan',
                'middle_name'           => '',
                'last_name'             => 'Sen',
                'employee_id'           => 'MMT004',
                'status'                => 1,
                'password'              => Hash::make('Passw0rd@123'),
                'password_updated'      => 1,
                'onboard_confirmed'     => 1,
            ]
        );

        $user->assignRole('admin');

        $user = User::updateOrCreate(
            ['email'    => 's.souvik@magicminds.io'],
            [
                'honorific'             => 'Mr.',
                'first_name'            => 'Souvik',
                'middle_name'           => '',
                'last_name'             => 'Samanta',
                'employee_id'           => 'MMT005',
                'status'                => 1,
                'password'              => Hash::make('Passw0rd@123'),
                'password_updated'      => 1,
                'onboard_confirmed'     => 1,
            ]
        );

        $user->assignRole('admin');

        $user = User::updateOrCreate(
            ['email'    => 'a.aman@magicminds.io'],
            [
                'honorific'             => 'Mr.',
                'first_name'            => 'Aman',
                'middle_name'           => '',
                'last_name'             => 'Aasim',
                'employee_id'           => 'MMT006',
                'status'                => 1,
                'password'              => Hash::make('Passw0rd@123'),
                'password_updated'      => 1,
                'onboard_confirmed'     => 1,
            ]
        );

        $user->assignRole('admin');

        $user = User::updateOrCreate(
            ['email'    => 's.priyajit@magicminds.io'],
            [
                'honorific'             => 'Mr.',
                'first_name'            => 'Priyajit',
                'middle_name'           => '',
                'last_name'             => 'Samanta',
                'employee_id'           => 'MMT007',
                'status'                => 1,
                'password'              => Hash::make('Passw0rd@123'),
                'password_updated'      => 1,
                'onboard_confirmed'     => 1,
            ]
        );

        $user->assignRole('admin');
    }
}
