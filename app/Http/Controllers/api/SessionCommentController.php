<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\SessionComment;
use Illuminate\Http\Request;

class SessionCommentController extends Controller
{
    public function index(){

        $sessioncomments=SessionComment::all();
        
        if ($sessioncomments->isEmpty()) {
            return response([
                'message' => 'No session comments found',
            ], 404); 
        }

        return response([
            'message'=>'session comments retreived successfully',
            'data'=>$sessioncomments
        ],200);



    }



    public function store(Request $request){
        $user=auth()->user();
        $request->validate([
            'session_id' => 'required',
            'comment' => 'nullable|string|max:500',
        ]);


        $sessioncomment = SessionComment::create([
            'session_id' => $request->session_id,
            'student_id' => $user->id,
            'comment' => $request->comment,
        ]);

        return response(['message' => 'session comment created successfully', 'data' => $sessioncomment],201);
    }



    
    public function destroy($id){
        $user=auth()->user();
        $sessioncomment=SessionComment::find($id);
        if(!$sessioncomment){
            return response([
                'message'=>'sessioncomment not found'
            ],404);
        }

        if($sessioncomment->student_id!=$user->id){
            return response(['message'=>'this is not your comment to delete'],400);
        }

        $sessioncomment->delete();
        return response([
            'message'=>'sessioncomment deleted successfully',
        ],200);
    }
}
