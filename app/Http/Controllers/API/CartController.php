<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Medican;
use App\Models\product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cart::whereRelation('user', 'user_id', auth()->id())->get();
    }

    public function updateQuantity($id, $type = 1)
    {
        $validation =  Validator::make(
            ['id' => $id, 'type' => $type],
            [
                'id' => ['required', 'exists:carts,id'],
                'type' => ['required', 'integer', 'in:1,2'],
            ]
        );
        if ($validation->fails()) {
            return $validation->errors(); //->first()
        }
        if ($type == 1) {
            $cart_quantity = Cart::where('id', $id)->get();
            if ($cart_quantity->medicne_id) {
                $total_quantity = Medican::where('id', $cart_quantity->medican_id)->get()['quantity'];
            } elseif ($cart_quantity->product_id) {
                $total_quantity = product::where('id', $cart_quantity->product_id)->get()['quantity'];
            }

            if ($cart_quantity->quantity + 1 <= $total_quantity) {
                Cart::where('id', $id)->increment('quantity', 1);
                return response()->json([
                    "message" => "success"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Out of stock."
                ], 400);
            }
        } elseif ($type == 2) {
            $cart_quantity = Cart::where('id', $id)->get();
            if ($cart_quantity->medicne_id) {
                $total_quantity = Medican::where('id', $cart_quantity->medican_id)->get()['quantity'];
            } elseif ($cart_quantity->product_id) {
                $total_quantity = product::where('id', $cart_quantity->product_id)->get()['quantity'];
            }

            if ($cart_quantity - 1 >= 1) {
                Cart::where('id', $id)->decrement('quantity', 1);
                return response()->json([
                    "message" => "success"
                ], 200);
            } else {
                return response()->json([
                    "message" => "you cant down to zero"
                ], 400);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'medicne_id' => ['required_without:product_id', 'exists:medicnes,id'],
            'product_id' => ['required_without:medicne_id', 'exists:products,id']
        ]);

        $cart = new Cart();
        $cart->medicne_id = $request->medicne_id ?? null;
        $cart->product_id = $request->product_id ?? null;
        $cart->save();
        if ($cart) {
            return response()->json([
                "message" => "success"
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $validation =  Validator::make(
            ['id' => $id],
            [
                'id' => ['required', 'exists:carts,id'],
            ]
        );
        if (!$validation->fails()) {
            $delete = Cart::find($id)->delete();
        } else {
            return $validation->errors(); //->first()
        }
    }
}
