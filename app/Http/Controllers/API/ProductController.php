<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use App\Models\Medican;
use App\Models\product;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    public function add_product(Request $request)
    {
        //$pharmacy_id = auth()->user()->id;

        $validatedData = $request->validate([
            'name' => 'required|max:25',
            'img' => 'required|max:25',
            'description' => 'required|max:25|nullable',
            'b_price' => 'required|max:25|nullable',
            'a_price' => 'required|max:25|nullable',
            'quantity' => 'required|nullable',
            'category' => 'required|nullable'

        ]);



        $p = product::where([
            "name" => $request->name,
        ])->first();

        if ($p) {

            $p->quantity += $request->quantity;

            $p->save();

            return response()->json([
                'success' => true,
                'message' => 'product added successfully.',
                'data' => $p
            ], 201);
        } else {

            $p = new product();

            $file = $request->file('img');
            $imageName = time() . '.' . $file->extension();
            $imagePath = public_path() . '/files';

            $p->name = $request->name;
            $p->description = $request->description;
            $p->img = $imageName;
            $p->quantity = $request->quantity;
            $p->b_price = $request->b_price;
            $p->a_price = $request->a_price;
             $p->category = $request->category;

            $p->save();
            $file->move($imagePath, $imageName);

            return response()->json([
                'success' => true,
                'message' => 'product added successfully.',
                'data' => $p
            ], 201);
        }
    }

    public function totalproduct()
    {


        $p = product::all();
        return response()->json([
            "status" => 1,
            "message" => "Total your product ",
            "data" => $p
        ]);
    }


    public function deleteProduct($id)
    {



        if (product::where([
            "id" => $id,

        ])->exists()) {

            $p = product::find($id);

            $p->delete();

            return response()->json([
                "status" => 1,
                "message" => "Product deleted successfully"
            ]);
        } else {

            return response()->json([
                "status" => 0,
                "message" => "Product not found"
            ]);
        }
    }

    public function update_product_id(Request $request, $id)
    {

        $p = product::where([
            "id" => $id,
        ])->first();

        if ($p) {
            if ($request->file('img')) {
                $file = $request->file('img');
                $imageName = time() . '.' . $file->extension();
                $imagePath = public_path() . '/files';
            }
            $p->name = isset($request->name) ? $request->name : $p->name;
            $p->quantity = isset($request->quantity) ? $request->quantity : $p->quantity;
            $p->description = isset($request->description) ? $request->description : $p->description;
            $p->img = $imageName ?? $p->img;
            $p->b_price = isset($request->b_price) ? $request->b_price : $p->b_price;
            $p->a_price = isset($request->a_price) ? $request->a_price : $p->a_price;

            $p->save();
            if ($request->file('img')) {
                $file->move($imagePath, $imageName);
            }
            return response()->json([
                "status" => 1,
                "message" => "product data has been updated"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "product doesn't exists"
            ]);
        }
    }

    public function update_product(Request $request)
    {

        $p = product::where([
            "name" =>  $request->name,
        ])->first();

        if ($p) {
            if ($request->file('img')) {
                $file = $request->file('img');
                $imageName = time() . '.' . $file->extension();
                $imagePath = public_path() . '/files';
            }

            $p->name = isset($request->name) ? $request->name : $p->name;
            $p->quantity = isset($request->quantity) ? $request->quantity : $p->quantity;
            $p->description = isset($request->description) ? $request->description : $p->description;
            $p->img = $imageName ?? $p->img;
            $p->b_price = isset($request->b_price) ? $request->b_price : $p->b_price;
            $p->a_price = isset($request->a_price) ? $request->a_price : $p->a_price;

            $p->save();
            if ($request->file('img')) {
                $file->move($imagePath, $imageName);
            }

            return response()->json([
                "status" => 1,
                "message" => "product data has been updated"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "product doesn't exists"
            ]);
        }
    }
}
