<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Earning;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 31; $i++) {

            $today = Carbon::today();

            $day = $today->addDay($i);

            $e = new Earning();

            $e->date = $day;
            $e->revenue = $i + 100;
            $e->cost = $i + 10;
            $e->earnings = $i + 90;

            $e->save();
        }

        // Earning::created([
        //     'date' => '$today',
        //     'revenue' => '$dailyRevenue',
        //     'cost' => '$dailyCost',
        //     'earnings' => '$dailyearnings',
        // ]);
    }
}
