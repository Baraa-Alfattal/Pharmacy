<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Medican;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends Controller
{
    // USER REGISTER API - POST
    public function register(Request $request)
    {
        // validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:pharmacies",
            "password" => "required|confirmed",
            //"role_id" => "required|integer|max:3",
            "number_phone" => "required|min:2|max:10",
            "site" => "required",
            "img" => "nullable|image|mimes:jpg,png,jpeg",
            "start_time" => "required",
            "end_time" => "required",

        ]);

        // create user data + save
        $pharmacy = new Pharmacy();

        $file = $request->file('img');
        $imageName = time() . '.' . $file->extension();
        $imagePath = public_path() . '/files';

        $pharmacy->name = $request->name;
        $pharmacy->email = $request->email;
        $pharmacy->password = bcrypt($request->password);
        //$pharmacy->role_id = $request->role_id;
        $pharmacy->number_phone = $request->number_phone;
        $pharmacy->site = $request->site;
        $pharmacy->img = $imageName;
        $pharmacy->start_time = $request->start_time;
        $pharmacy->end_time = $request->end_time;

        $pharmacy->save();
        $file->move($imagePath, $imageName);

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
            "email" => "required|email|exists:pharmacies,email",
            "password" => "required"
        ]);


        // check user
        $pharmacy = Pharmacy::where("email", $request->email)->first();

        if ($pharmacy) {

            if (Hash::check($request->password, $pharmacy->password)) {

                // create a token
                $token = $pharmacy->createToken("auth_token")->plainTextToken;

                /// send a response
                return response()->json([
                    "status" => 200,
                    "message" => "Pharmacy logged in successfully",
                    "access_token" => $token,
                    "info" => $pharmacy
                ], 200);
            } else {

                return response()->json([
                    "status" => 401,
                    "message" => "Password didn't match"
                ], 401);
            }
        } else {

            return response()->json([
                "status" => 404,
                "message" => "Pharmacy not found"
            ], 404);
        }
    }

    // USER PROFILE API - GET
    public function profile()
    {
        return response()->json([
            "status" => 200,
            "message" => "User profile data",
            "data" => auth()->user()
        ], 200);
    }

    // USER LOGOUT API - GET
    public function logout()
    {

        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 200,
            "message" => "Pharmacy logged out successfully"
        ], 200);
    }

    // USER update API - post
    public function ph_update(Request $request)
    {

        $pharmacy_id = auth()->user()->id;

        // $request->validate([
        //     "email" => "required|email|unique:pharmacies",
        // ]);

        if ($p = Pharmacy::find($pharmacy_id)) {
            if ($request->file('img')) {
                $file = $request->file('img');
                $imageName = time() . '.' . $file->extension();
                $imagePath = public_path() . '/files';
            }

            $p->name = isset($request->name) ? $request->name : $p->name;
            $p->email = isset($request->email) ? $request->email : $p->email;
            $p->site = isset($request->site) ? $request->site : $p->site;
            $p->start_time = isset($request->start_time) ? $request->start_time : $p->start_time;
            $p->end_time = isset($request->end_time) ? $request->end_time : $p->end_time;
            $p->img = $imageName ?? $p->img;
            $p->password = isset($request->password) ? bcrypt($request->password) : $p->password;
            $p->number_phone = isset($request->number_phone) ? $request->number_phone : $p->number_phone;

            $p->save();
            if ($request->file('img')) {
                $file->move($imagePath, $imageName);
            }

            return response()->json([
                "status" => 200,
                "message" => "pharmacy data has been updated"
            ], 200);
        } else {
            return response()->json([
                "status" => 404,
                "message" => "pharmacy doesn't exists"
            ], 404);
        }
    }

    public function add(Request $request)
    {
        //$pharmacy_id = auth()->user()->id;

        $validatedData = $request->validate([
            'name' => 'required|max:25',
            'scientific_name' => 'required|max:1024',
            'company_name' => 'required|max:255',
            'category' => 'required|max:255',
            'active_ingredient' => 'required|max:1024',
            'img' => 'nullable|image|mimes:jpg,png,jpeg',
            'uses_for' => 'required|max:1024',
            'effects' => 'required|max:1024',
            'quantity' => 'required',
            'expiry_date' => 'required',
            'b_price' => 'required',
            'a_price' => 'required',

        ]);


        $medican = Medican::where([
            "name" => $request->name,
            "company_name" => $request->company_name
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


            // $file = $request->file('img');
            // $imageName = time() . '.' . $file->extension();
            // $imagePath = public_path() . '/files';

            if ($request->file('img')) {
                $file = $request->file('img');
                $imageName = time() . '.' . $file->extension();
                $imagePath = public_path() . '/files';
            }

            $medican = new Medican();

            //$medican->pharmacy_id = auth()->user()->id;
            $medican->name = $request->name;
            $medican->scientific_name = $request->scientific_name;
            $medican->company_name = $request->company_name;
            $medican->category = $request->category;
            $medican->active_ingredient = $request->active_ingredient;
            $medican->img = $imageName ?? null;
            $medican->uses_for = $request->uses_for;
            $medican->quantity = $request->quantity;
            $medican->effects = $request->effects;
            $medican->expiry_date = $request->expiry_date;
            $medican->b_price = $request->b_price;
            $medican->a_price = $request->a_price;
            //$medican->user_id = auth()->id();


            $medican->effects = $request->effects;
            $medican->save();
            if ($request->file('img'))
                $file->move($imagePath, $imageName);

            return response()->json([
                'success' => 201,
                'message' => 'medican added successfully.',
                'data' => $medican
            ], 201);
        }
    }

    public function totalmedicans()
    {
        // $id = auth()->user()->id;

        // $p = Pharmacy::find($id)->medicans;

        // return response()->json([
        //     "status" => 1,
        //     "message" => "Total your medicans ",
        //     "data" => $p
        // ]);

        $medican = Medican::all();
        return response()->json([
            "status" => 200,
            "message" => "Total your medicans ",
            "data" => $medican
        ], 200);
    }

    // DELETE MEDICAN API - GET
    public function deleteMedican($id)
    {
        $validation =  Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer', 'exists:medicans,id']]
        );
        if ($validation->fails()) {
            return $validation->errors();
        }
        $Medican = Medican::where("id", $id)->first();
        if ($Medican) {
            $Medican->delete();
            return response()->json([
                "status" => 200,
                "message" => "Medican deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "status" => 404,
                "message" => "Medican not found"
            ], 404);
        }
    }

    public function update_medi_id(Request $request, $id)
    {
 
        $medican = Medican::where("id", $id)->first();

        if ($medican) {
            if ($request->file('img')) {
                $file = $request->file('img');
                $imageName = time() . '.' . $file->extension();
                $imagePath = public_path() . '/files';
            }

            $medican->name = isset($request->name) ? $request->name : $medican->name;
            $medican->scientific_name = isset($request->scientific_name) ? $request->scientific_name : $medican->scientific_name;
            $medican->company_name = isset($request->company_name) ? $request->company_name : $medican->company_name;
            $medican->quantity = isset($request->quantity) ? $request->quantity : $medican->quantity;
            $medican->category = isset($request->category) ? $request->category : $medican->category;
            $medican->active_ingredient = isset($request->active_ingredient) ? $request->active_ingredient : $medican->active_ingredient;
            $medican->img = $imageName ?? $medican->img;
            $medican->uses_for = isset($request->uses_for) ? $request->uses_for : $medican->uses_for;
            $medican->effects = isset($request->effects) ? $request->effects : $medican->effects;
            $medican->expiry_date = isset($request->expiry_date) ? $request->expiry_date : $medican->expiry_date;
            $medican->b_price = isset($request->b_price) ? $request->b_price : $medican->b_price;
            $medican->a_price = isset($request->a_price) ? $request->a_price : $medican->a_price;

            $medican->save();
            if ($request->file('img'))
                $file->move($imagePath, $imageName);

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
            "name" =>  $request->name,
            //'pharmacy_id' => auth()->user()->id
        ])->first();

        if ($medican) {

            if ($request->file('img')) {
                $file = $request->file('img');
                $imageName = time() . '.' . $file->extension();
                $imagePath = public_path() . '/files';
            }

            $medican->name = isset($request->name) ? $request->name : $medican->name;
            $medican->scientific_name = isset($request->scientific_name) ? $request->scientific_name : $medican->scientific_name;
            $medican->company_name = isset($request->company_name) ? $request->company_name : $medican->company_name;
            $medican->quantity = isset($request->quantity) ? $request->quantity : $medican->quantity;
            $medican->category = isset($request->category) ? $request->category : $medican->category;
            $medican->active_ingredient = isset($request->active_ingredient) ? $request->active_ingredient : $medican->active_ingredient;
            $medican->img = $imageName ?? $medican->img;
            $medican->uses_for = isset($request->uses_for) ? $request->uses_for : $medican->uses_for;
            $medican->effects = isset($request->effects) ? $request->effects : $medican->effects;
            $medican->expiry_date = isset($request->expiry_date) ? $request->expiry_date : $medican->expiry_date;
            $medican->b_price = isset($request->b_price) ? $request->b_price : $medican->b_price;
            $medican->a_price = isset($request->a_price) ? $request->a_price : $medican->a_price;

            $medican->save();
            if ($request->file('img')) {
                $file->move($imagePath, $imageName);
            }

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

    public function search(Request $request)
    {
        $request->validate([
            'search_text' => ['required', 'string'],
            'search_type' => [
                'required', 'string',
                'in:name,scientific_name,company_name,category,active_ingredient,description'
            ]
        ]);

        $query = Medican::where($request->search_type, 'like', '%' . $request->search_text . '%')->paginate();

        if ($query->IsEmpty())
            return response()->json([
                'code' => '404',
                'status' => 'Fail',
                'message' => 'No Results Found',
                'data' => [],
            ], 404);

        else
            return response()->json([
                'code' => '200',
                'status' => 'success',
                'message' => 'Search Results',
                'data' => $query,
            ], 200);

        // $query = Medican::query()
        //     ->select(

        //         'id',
        //         'pharmacy_id',
        //         'name',
        //         'scientific_name',
        //         'company_name',
        //         'category',
        //         'active_ingredient',
        //         'price',
        //         'description',
        //         // 'id',
        //         // 'active_ingredient',
        //         // 'brand_name',
        //         // 'profit',//
        //         // 'strength',//
        //         // 'marketing_status',//
        //         // 'essential',//
        //         // 'EPC',//
        //         // 'MOA',//
        //         // 'CS',//
        //         // 'PE',//
        //         // 'guide',//ارشادات
        //         // 'route'//جرعة
        //     )
        //     ->orderBy('id');

        // $columns = [
        //     'id',
        //     'pharmacy_id',
        //     'name',
        //     'scientific_name',
        //     'company_name',
        //     'category',
        //     'active_ingredient',
        //     'price',
        //     'description',
        //     // 'active_ingredient',
        //     // 'brand_name', 'profit',
        //     // 'strength', 'marketing_status',
        //     // 'essential',
        //     // 'EPC', 'MOA', 'CS', 'PE', 'guide', 'route'
        // ];

        // $searchTerm = $request->input('search_term'); // استلام البحث من الطلب

        // foreach ($columns as $c) {

        //     $query->orwhere($c, 'like', '%' . $searchTerm . '%');
        // }
        // if ($query->count()) {

        //     $drugs = $query->get();
        //     return response()->json(['Search Results' => $drugs]);
        // } else {
        //     return response()->json(['message' => 'No Results Found']);
        // }
    } //search of all drugs
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
