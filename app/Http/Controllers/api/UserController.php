<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function showadmins(){
        $admins = User::where('role', 'admin')->get();
        return response([
            'message'=>'admins retreived successfully',
            'admins'=>$admins
        ],200);
    }
    
    
    public function showinstructors(){
        $instructors = User::where('role', 'instructor')->get();
        return response([
            'message'=>'instructors retreived successfully',
            'admins'=>$instructors
        ],200);
    }
    
    
    public function showstudents(){
        $students = User::where('role', 'student')->get();
        return response([
            'message'=>'students retreived successfully',
            'admins'=>$students
        ],200);
    }
    
    public function deleteaccount(Request $request ,$id){

        $user=User::find($id);
        if(!$user){
            return response(['message'=>'user not found'],404);
        }

        if(!$request->has('confirm')||!$request->boolean('confirm')){
            return response([
                'message'=>"Are you sure u want to delete this user",
                'data'=>$user,
                'confirm'=>true
            ],200);
        }

        $user->delete();
        return response([
            'message'=>'user deleted successfully'
        ],200);
    }


    public function update(request $request){

        $user=Auth::user();

        $request->validate([
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'address' => 'required|string',
            'phone' => 'required|string|min:10',
        ]);
    
        // Prepare updated data
        
        
        
        // Check if there are actual changes
        if ($request->first_name==$user->first_name &&
        $request->last_name==$user->last_name &&
        $request->address==$user->address &&
        $request->phone==$user->phone
        
        ) {
            return response()->json(['message' => 'No updates were made'], 200);
        }

        
        // Update user details
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;
        $user->phone = $request->phone;
        
        $user->save();
    
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);


    }
    
}
