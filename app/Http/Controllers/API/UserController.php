<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            "email" => "required|email|unique:users",
            "b_day" => "required",
            "number" => "required|min:2|max:10",
            "password" => "required|confirmed|max:20"
        ]);

        // create user data + save
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->b_day = $request->b_day;
        $user->number = $request->number;
        $user->password = bcrypt($request->password);

        $user->save();

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
            "email" => "required|email",
            "password" => "required"
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
        $user = User::where("email", "=", $request->email)->first();

        if (isset($user->id)) {

            if (Hash::check($request->password, $user->password)) {

                // create a token
                $token = $user->createToken("auth_token")->plainTextToken;

                /// send a response
                return response()->json([
                    "status" => 1,
                    "message" => "user logged in successfully",
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
                "message" => "user not found"
            ], 404);
        }
    }

    // USER PROFILE API - GET
    public function profile()
    {
        $user_data = auth()->user();

        return response()->json([
            "status" => 1,
            "message" => "User profile data",
            "data" => $user_data
        ]);
    }

    // USER LOGOUT API - GET
    public function logout()
    {
      
        auth()->user()->tokens()->delete(); // هذا السطر صحيح ولكن محرر الاكواد لا يتعرف علية

        return response()->json([
            "status" => 1,
            "message" => "User logged out successfully"
        ]);
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
}
