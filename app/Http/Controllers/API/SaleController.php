<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Medican;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as AuthUser;


class SaleController extends Controller
{
    // POST- ADD SALES

    public function sale(Request $request)
    {
        $user_id = auth()->user()->id;

        // validation
        $request->validate([
            //"pharmacy_id" => "required|integer",
            "medican_id" => "required|integer",
            "quantity" => "required|integer",
        ]);

        // $p = Pharmacy::where([
        //     'id' => $request->pharmacy_id
        // ])->first();

        // if (!$p) {
        //     return response()->json([
        //         "status" => 0,
        //         "message" => "pharmacy  doesn't exists"
        //     ]);
        // }

        $medican = Medican::where([
            'id' => $request->medican_id
        ])->first();

        if (!$medican) {
            return response()->json([
                "status" => 0,
                "message" => "medican  doesn't exists"
            ]);
        }

        if ($medican->quantity < $request->quantity) {
            return response()->json([
                "status" => 0,
                "message" => "the quantity doesn't exist"
            ]);
        }

        // if ($medican && $p)
        if ($medican )  {

            $sale = new Sale();

            $sale->user_id = auth()->user()->id;
            //$sale->pharmacy_id = $request->pharmacy_id;
            $sale->medican_id = $request->medican_id;
           // $sale->medican_name = $medican->name;
            $sale->quantity = $request->quantity;
            $sale->total = $request->quantity * $medican->a_price;
            $sale->earnings = ($request->quantity * $medican->a_price)-
                              ($request->quantity * $medican->b_price);
            $sale->save();

            //تعديل كمية الدواء التي في المخزن بعد الشراء
            $medican->quantity -= $request->quantity;
            $medican->save();

            return response()->json([
                "status" => 1,
                "message" => "sale data has been added",
                'data' => $sale
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "medican  doesn't exists"
            ]);
        }
    }

    // POST- ADD SALES BY NAME
    public function sale_name(Request $request)
    {
        $user_id = auth()->user()->id;

        // validation
        $request->validate([
            // "pharmacy" => "required",
            "medican_name" => "required",
            "quantity" => "required|integer",
        ]);

        // $pharmacy = Pharmacy::where([
        //     'name' => $request->pharmacy
        // ])->first();

        // if (!$pharmacy) {
        //     return response()->json([
        //         "status" => 0,
        //         "message" => "pharmacy  doesn't exists"
        //     ]);
        // }

        $medican = Medican::where([
            'name' => $request->medican_name
        ])->first();

        if (!$medican) {
            return response()->json([
                "status" => 0,
                "message" => "medican  doesn't exists"
            ]);
        }

        if ($medican->quantity < $request->quantity) {
            return response()->json([
                "status" => 0,
                "message" => "the quantity doesn't exist"
            ]);
        }

        if ($medican) {

            $sale = new Sale();

            $sale->user_id = auth()->user()->id;
           // $sale->pharmacy_id = $pharmacy->id;
            $sale->medican_id = $medican->id;
            //$sale->medican_name = $request->medican_name;
            $sale->quantity = $request->quantity;
            $sale->total = $request->quantity * $medican->price;
            $sale->earnings = ($request->quantity * $medican->a_price)-
                              ($request->quantity * $medican->b_price);

            $sale->save();

            //تعديل كمية الدواء التي في المخزن بعد الشراء

            $medican->quantity -= $request->quantity;
            $medican->save();

            return response()->json([
                "status" => 1,
                "message" => "sale data has been added",
                'data' => $sale
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "medican  doesn't exists"
            ]);
        }
    }


}
