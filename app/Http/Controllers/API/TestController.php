<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Earning;

class TestController extends Controller
{
    public function daily_handle()
    {

        $today = Carbon::today();

        $e = Earning::where([
            'date' => $today,
        ])->first();

        if ($e) {
            return response()->json([
                "status" => 1,
                "message" => "your daily earnings",
                'data' => $e
            ]);
        } else {
            $sales = Sale::whereDate('created_at', $today)->get();

            $dailyRevenue = $sales->sum('total');
            $dailyCost = $sales->sum('cost');
            $dailyearnings = $sales->sum('earnings');

            $e = new Earning();
            $e->pharmacie_id = 1;
            $e->date = $today;
            $e->revenue = $dailyRevenue;
            $e->cost = $dailyCost;
            $e->earnings = $dailyearnings;

            $e->save();

            return response()->json([
                "status" => 1,
                "message" => "your daily earnings",
                'data' => $e
            ]);
        }
    }

    public function get_daily_earnings(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|max:25',
        ]);

        $e = Earning::where([
            'date' => $request->date,
        ])->first();

        if ($e) {
            return response()->json([
                "status" => 1,
                "message" => "your daily earnings",
                'data' => $e
            ]);
        } else {

            $sales = Sale::whereDate('created_at', $request->date)->get();

            $dailyRevenue = $sales->sum('total');
            $dailyCost = $sales->sum('cost');
            $dailyearnings = $sales->sum('earnings');

            $e = new Earning();
            $e->pharmacie_id = 1;
            $e->date = $request->date;
            $e->revenue = $dailyRevenue;
            $e->cost = $dailyCost;
            $e->earnings = $dailyearnings;

            $e->save();

            return response()->json([
                "status" => 1,
                "message" => "your daily earnings",
                'data' => $e
            ]);
        }
    }


    public function monthly_handle()
    {
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');

        $sales = Sale::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $monthlyRevenue = $sales->sum('total');
        $monthlyCost = $sales->sum('cost');
        $monthlyearnings = $sales->sum('earnings');

        $e = new Earning();

        $e->pharmacie_id = 1;
        $e->date = null;
        $e->revenue = $monthlyRevenue;
        $e->cost = $monthlyCost;
        $e->earnings = $monthlyearnings;

        $e->save();

        return response()->json([
            "status" => 1,
            "message" => "your monthly earnings",
            'data' => $e
        ]);
    }

    public function get_7day()
    {
        $today = Carbon::today();
        $de = [];
        // $day = $today->subDay/s($i);

        for ($i = 0; $i < 7; $i++) {

            $e = Earning::whereDate('date', '=', Carbon::today()->subDay($i))->get();
            //$e  = Earning::latest()->take(7)->get();
            foreach ($e as $e) {
                $de[$e->date] = $e->earnings;
            }
        }

        return response()->json([
            "status" => 1,
            "message" => "your earnings",
            'data' => $de
        ]);
    }



    public function daily()
    {
        $today = Carbon::today();
        $e = Earning::whereDate('date', $today)->firstOrFail();
        return response()->json([
            'revenue' => $e->revenue,
            'cost' => $e->cost,
        ]);
    }

    public function monthly()
    {
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $e = Earning::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->firstOrFail();
        return response()->json([
            'revenue' => $e->revenue,
            'cost' => $e->cost,
        ]);
    }
}
