<?php

namespace Database\Seeders;

use App\Models\MotivationalQuote;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivationalQuoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quotes = [
            [
                'quote' => 'Embrace challenges as opportunities for growth, and watch your strength unfold.',
                'date'  =>  Carbon::create(2024, 1, 31),
            ],
            [
                'quote' => 'In the dance of life, let passion lead and purpose be your guide.',
                'date'  =>  Carbon::create(2024, 2, 1),
            ],
            [
                'quote' => "Stars can't shine without darkness; your resilience is the light within.",
                'date'  =>  Carbon::create(2024, 12, 31),
            ],
            [
                'quote' => 'Dream big, work hard, and let your success be the loudest applause.',
                'date'  =>  Carbon::create(2024, 1, 30),
            ],
            [
                'quote' => 'Every step forward is a victory over fear, and every stumble is a lesson in courage.',
                'date'  =>  Carbon::create(2024, 1, 22),
            ],
            [
                'quote' => 'Dare to be a beacon of kindness, and let your light illuminate the world.',
                'date'  =>  Carbon::create(2024, 1, 12),
            ],
        ];

        foreach($quotes as $quote) {
            MotivationalQuote::create([
                'quote'             =>  $quote['quote'],
                'said_by'           =>  '',
                'user_id'           =>  1,
                'display_date'      =>  $quote['date'],
                'status'            =>  1,
            ]);
        }
    }
}
