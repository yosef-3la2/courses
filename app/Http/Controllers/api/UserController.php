<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'first_name'=>'required|string|min:3',
            'last_name'=>'required|string|min:3',
            'email'=>'required|email|unique:users,email',
            'address'=>'required|string',
            'password'=>'required|confirmed|string',
            'role' => 'required|in:admin,instructor,student',
            'phone' => 'required|string|min:10',

        ]);

        $user=User::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'address'=>$request->address,
            'role'=>$request->role,
            'phone'=>$request->phone
        ]); 

        $token=$user->createToken('myToken')->plainTextToken;

        return response([
            'message'=>'user created successfully',
            'data'=>$user,
            'token'=>$token
        ],201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string'
        ]);

        $user=User::where('email',$request->email)->first();

        if(!$user||!Hash::check($request->password,$user->password)){
            return response([
                'message'=>'email or password is incorrect'
            ],400);
        }

        $token=$user->createToken('myToken')->plainTextToken;

        return response([
            'message'=>'welcome back'."  ".$user->first_name,
            'data'=>$user,
            'token'=>$token 
        ],200);

    }

    public function logout(){
        auth()->user()->currentAccesstoken()->delete();
        return response([
            'message'=>'you have logged out from your account'
        ],200);
    }

     
    
    
    
}
