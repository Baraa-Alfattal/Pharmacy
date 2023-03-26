<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Medican;

use Illuminate\Support\Facades\Hash;

class PharmacyController extends Controller
{
    // USER REGISTER API - POST
    public function register(Request $request)
    {
        // validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:pharmacies",
            "password" => "required|confirmed"
        ]);

        // create user data + save
        $pharmacy = new Pharmacy();

        $pharmacy->name = $request->name;
        $pharmacy->email = $request->email;
        $pharmacy->password = bcrypt($request->password);

        $pharmacy->save();

        // send response
        return response()->json([
            "status" => 1,
            "message" => "Pharmacy registered successfully"
        ], 200);
    }

    // USER LOGIN API - POST
    public function login(Request $request)
    {
        // validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);


        // check user
        $pharmacy = Pharmacy::where("email", "=", $request->email)->first();

        if (isset($pharmacy->id)) {

            if (Hash::check($request->password, $pharmacy->password)) {

                // create a token
                $token = $pharmacy->createToken("auth_token")->plainTextToken;

                /// send a response
                return response()->json([
                    "status" => 1,
                    "message" => "Pharmacy logged in successfully",
                    "access_token" => $token
                ]);
            } else {

                return response()->json([
                    "status" => 0,
                    "message" => "Password didn't match"
                ], 404);
            }
        } else {

            return response()->json([
                "status" => 0,
                "message" => "Pharmacy not found"
            ], 404);
        }
    }

    // USER PROFILE API - GET
    public function profile()
    {
        $pharmacy_data = auth()->user();

        return response()->json([
            "status" => 1,
            "message" => "User profile data",
            "data" => $pharmacy_data
        ]);
    }

    // USER LOGOUT API - GET
    public function logout()
    {

        // auth()->user()->tokens()->delete(); // هذا السطر صحيح ولكن محرر الاكواد لا يتعرف علية

        return response()->json([
            "status" => 1,
            "message" => "Pharmacy logged out successfully"
        ]);
    }

    // USER update API - post
    public function ph_update(Request $request)
    {

        $pharmacy_id = auth()->user()->id;

        // $request->validate([
        //     "email" => "required|email|unique:pharmacies",
        // ]);

        if ($p = Pharmacy::find($pharmacy_id)) {

            $p->name = isset($request->name) ? $request->name : $p->name;
            $p->email = isset($request->email) ? $request->email : $p->email;
            $p->site = isset($request->site) ? $request->site : $p->site;
            $p->time = isset($request->time) ? $request->time : $p->time;
            $p->day = isset($request->day) ? $request->day : $p->day;
            $p->password = isset($request->password) ? bcrypt($request->password) : $p->password;

            $p->save();

            return response()->json([
                "status" => 1,
                "message" => "pharmacy data has been updated"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "pharmacy doesn't exists"
            ]);
        }
    }

    public function add(Request $request)
    {
        $pharmacy_id = auth()->user()->id;

        $validatedData = $request->validate([
            'name' => 'required|max:25',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
            'quantity' => 'nullable'
        ]);

        $medican = Medican::where([
            "name" => $request->name,
            'pharmacy_id' => auth()->user()->id
        ])->first();

        if ($medican) {

            $medican->quantity += $request->quantity;

            $medican->save();

            return response()->json([
                'success' => true,
                'message' => 'medican added successfully.',
                'data' => $medican
            ], 201);
        } else {

            $medican = new Medican();

            $medican->pharmacy_id = auth()->user()->id;
            $medican->name = $request->name;
            $medican->price = $request->price;
            $medican->description = $request->description;
            $medican->quantity = $request->quantity;

            $medican->save();

            return response()->json([
                'success' => true,
                'message' => 'medican added successfully.',
                'data' => $medican
            ], 201);
        }
    }

    public function totalmedicans()
    {
        $id = auth()->user()->id;

        $p = Pharmacy::find($id)->medicans;

        return response()->json([
            "status" => 1,
            "message" => "Total your medicans ",
            "data" => $p
        ]);
    }

    // DELETE MEDICAN API - GET
    public function deleteMedican($id)
    {

        $pharmacy_id = auth()->user()->id;

        if (Medican::where([
            "id" => $id,
            "pharmacy_id" => $pharmacy_id
        ])->exists()) {

            $Medican = Medican::find($id);

            $Medican->delete();

            return response()->json([
                "status" => 1,
                "message" => "Medican deleted successfully"
            ]);
        } else {

            return response()->json([
                "status" => 0,
                "message" => "Medican not found"
            ]);
        }
    }

    public function update_medi_id(Request $request,$id)
    {
       
        $medican = Medican::where([
            "id" => $id,
            'pharmacy_id' => auth()->user()->id
        ])->first();
        
        if ($medican) {

            $medican->name = isset($request->name) ? $request->name : $medican->name;
            $medican->price = isset($request->price) ? $request->price : $medican->price;
            $medican->description = isset($request->description) ? $request->description : $medican->description;
            $medican->quantity = isset($request->quantity) ? $request->quantity : $medican->quantity;

            $medican->save();

            return response()->json([
                "status" => 1,
                "message" => "medican data has been updated"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "medican doesn't exists"
            ]);
        }
    }

    public function update_medi(Request $request)
    {
       
        $medican = Medican::where([
            "name" =>  $request->name ,
            'pharmacy_id' => auth()->user()->id
        ])->first();
        
        if ($medican) {

            $medican->name = isset($request->name) ? $request->name : $medican->name;
            $medican->price = isset($request->price) ? $request->price : $medican->price;
            $medican->description = isset($request->description) ? $request->description : $medican->description;
            $medican->quantity = isset($request->quantity) ? $request->quantity : $medican->quantity;

            $medican->save();

            return response()->json([
                "status" => 1,
                "message" => "medican data has been updated"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "medican doesn't exists"
            ]);
        }
    }
}




/*

public function store(Request $request) {
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable',
        'image' => 'required|image'
    ]);

    $drug = Drug::where('name', $validatedData['name'])->first();

    if ($drug) {
        $drug->quantity += 1;
        $drug->save();
    } else {
        $imagePath = $validatedData['image']->store('drugs');

        $drug = new Drug();
        $drug->name = $validatedData['name'];
        $drug->quantity = 1;
        $drug->price = $validatedData['price'];
        $drug->description = $validatedData['description'];
        $drug->image_path = $imagePath;
        $drug->save();
    }

    return response()->json([
      'success' => true,
      'message' => 'Drug added successfully.',
      'data' => $drug
    ], 201);
}

*/
