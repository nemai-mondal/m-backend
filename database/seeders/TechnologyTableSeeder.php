<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $leaves = [
            0 => [
                'name'      =>  'Laravel',
            ],
            1 => [
                'name'      =>  'Php',
            ],
            3 => [
                'name'      =>  'CSS',
            ],
            4 => [
                'name'      =>  'HTML',
            ],
            5 => [
                'name'      =>  'Node.Js',
            ],
            6 => [
                'name'      =>  'Vue.js',
            ],
            7 => [
                'name'      =>  'Excel',
            ],
            8 => [
                'name'      =>  'GIT',
            ],
            9 => [
                'name'      =>  'AWS',
            ],
            10 => [
                'name'      =>  'AI',
            ]
        ];

        foreach($leaves as $leave) {

            Technology::create([
                'name'      =>  $leave['name'],
                'status'    =>  1
            ]);
        }
    }
}
