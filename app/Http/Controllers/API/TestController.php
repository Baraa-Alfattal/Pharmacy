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

        $sales = Sale::whereDate('created_at', $today)->get();

        $dailyRevenue = $sales->sum('total');
        $dailyCost = $sales->sum('cost');
        $dailyearnings= $sales->sum('earnings');

        $e = new Earning();

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


    public function monthly_handle()
    {
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');

        $sales = Sale::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $monthlyRevenue = $sales->sum('total');
        $monthlyCost = $sales->sum('cost');
        $monthlyearnings= $sales->sum('earnings');

        $e = new Earning();

        $e->date = Carbon::now();
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


    public function daily() {
        $today = Carbon::today();
        $e = Earning::whereDate('date', $today)->firstOrFail();
        return response()->json([
            'revenue' => $e->revenue,
            'cost' => $e->cost,
        ]);
    }

    public function monthly() {
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