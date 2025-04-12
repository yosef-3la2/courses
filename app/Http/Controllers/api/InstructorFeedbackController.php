<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\InstructorFeedback;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorFeedbackController extends Controller
{
    
    public function index(){

        $instructorfeedbacks=InstructorFeedback::all();
        
        if ($instructorfeedbacks->isEmpty()) {
            return response([
                'message' => 'No instructor feedbacks found',
            ], 404); 
        }

        return response([
            'message'=>'instructor feedbacks retreived successfully',
            'data'=>$instructorfeedbacks
        ],200);



    }



    public function store(Request $request){
        $user=auth()->user();
        $request->validate([
            'instructor_id' => 'required',
            'rating' => 'required|integer|min:0|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $instructor=User::find($request->instructor_id);
        if(!$instructor){
            return response(['message'=>'instructor not found'],400);
        }
        if($instructor->role!='instructor'){
            return response(['message'=>'this user is not instructor'],400);
        }


        $feedback = InstructorFeedback::create([
            'instructor_id' => $request->instructor_id,
            'student_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response(['message' => 'feedback created successfully', 'data' => $feedback],201);
    }



    
    public function destroy($id){
        $user=auth()->user();

        $instructorfeedback=InstructorFeedback::find($id);
        if(!$instructorfeedback){
            return response([
                'message'=>'instructorfeedback not found'
            ],404);
        }

        if($instructorfeedback->student_id!=$user->id){
            return response(['message'=>'this is not your feedback to delete'],400);
        }

        $instructorfeedback->delete();
        return response([
            'message'=>'instructorfeedback deleted successfully',
        ],200);
    }

}
