<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\disease;
use App\Models\FcmToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // USER REGISTER API - POST
    public function register(Request $request)
    {
        // validation
        $request->validate([
            "name" => "required|max:20",
            "email" => "required|email|unique:users,email",
            "b_day" => "required",
            "gender" => "required|min:2|max:10",
            "img" => "nullable|image|mimes:jpg,png,jpeg",
            "number" => "required|digits:10",
            "password" => "required|confirmed|max:20|min:8",
            "medicine_used" => "max:1024", //required|
            "medicine_allergies" => "max:1024", //required|
            "food_allergies" => "max:1024", //required|
            "have_disease" => "max:1024", //required|
            "another_disease" => "max:1024" //required|
        ]);

        // create user data + save
        $user = new User();
        if ($request->file('img')) {
            $file = $request->file('img');
            $imageName = time() . '.' . $file->extension();
            $imagePath = public_path() . '/files';
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->b_day = $request->b_day;
        $user->gender = $request->gender;
        $user->number = $request->number;
        $user->img = $imageName;
        $user->password = Hash::make($request->password);
        $user->medicine_used = $request->medicine_used ?? null;
        $user->medicine_allergies = $request->medicine_allergies ?? null;
        $user->food_allergies = $request->food_allergies ?? null;
        $user->have_disease = $request->have_disease ?? null;
        $user->another_disease = $request->another_disease ?? null;

        $user->save();
        if ($request->file('img'))
            $file->move($imagePath, $imageName);

        // send response
        return response()->json([
            "status" => 1,
            "message" => "User registered successfully"
        ], 200);
    }

    // USER LOGIN API - POST
    public function login(Request $request)
    {
        // validation
        $request->validate([
            "email" => "required|email|exists:users,email",
            "password" => "required|min:8"
        ]);

        // verify user + token

        // if (!$token = auth()->attempt(["email" => $request->email, "password" => $request->password])) {

        //     return response()->json([
        //         "status" => 0,
        //         "message" => "Invalid credentials"
        //     ]);
        // }

        // // send response
        // return response()->json([
        //     "status" => 1,
        //     "message" => "Logged in successfully",
        //     "access_token" => $token
        // ]);


        // check user
        $user = User::where("email", $request->email)->first();

        if ($user) {

            if (Hash::check($request->password, $user->password)) {

                // create a token
                $token = $user->createToken("auth_token")->plainTextToken;

                /// send a response
                return response()->json([
                    "status" => 200,
                    "message" => "user logged in AS User Successfully",
                    "access_token" => $token,
                    "info" => $user
                ]);
            } else {

                return response()->json([
                    "status" => 401,
                    "message" => "Password didn't match"
                ], 401);
            }
        } else {

            return response()->json([
                "status" => 404,
                "message" => "user not found"
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
            "message" => "User logged out successfully"
        ], 200);
    }

    // USER update API - post
    public function update(Request $request)
    {

        $users_id = auth()->user()->id;

        //  $request->validate([
        //      "email" => "required|email|unique:users",
        //  ]);

        if ($p = User::find($users_id)) {

            $p->name = isset($request->name) ? $request->name : $p->name;
            $p->email = isset($request->email) ? $request->email : $p->email;
            $p->b_day = isset($request->b_day) ? $request->b_day : $p->b_day;
            $p->password = isset($request->password) ? bcrypt($request->password) : $p->password;

            $p->save();

            return response()->json([
                "status" => 1,
                "message" => "user data has been updated"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "user doesn't exists"
            ]);
        }
    }

    function add_cart(Request $request)
    {
        $validation = Validator::make($request->paginate(), [
            "user_id" => "required|integer|exists:users,id",
            "medican_id" => "required|integer|exists:medicans,id",
            "product_id" => "required|integer|exists:products,id",
        ]);
    }

    function fcm_token(Request $request)
    {
        $request->validate([
            "user_id" => "required|integer|exists:users,id",
            "fcmtoken" => "required|unique:fcm_tokens,fcmtoken",
        ]);

        // create user data + save
        $fcmtoken = new FcmToken();

        $fcmtoken->user_id = $request->user_id;
        $fcmtoken->fcmtoken = $request->fcmtoken;

        $fcmtoken->save();


        return response()->json([
            "status" => 200,
            "message" => "successfully"
        ], 200);
    }



    public function add_notification(Request $request)
    {
        $request->validate([
            "user_id" => "required",
            "user_name" => "required",
            "medicine_name" => "required"
        ]);
        $users_id = auth()->user()->id;
        if ($p = User::find($users_id)) {
            $p->notifications =
                ["first notificatoin" => [
                    "user_id" => $request->user_id,
                    "user_name" => $request->user_name,
                    "medicine_name" => $request->medicine_name
                ]];
            $p->save();
            return response()->json([
                "status" => 1,
                "message" => "notifications success",
                "data" => $p->notifications, 200
            ]);
        }

        return response()->json(
            [
                "status" => 0,
                "message" => "User not found"
            ],
            200
        );
    }
}
