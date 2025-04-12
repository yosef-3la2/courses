<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseFeedback;
use App\Models\User;
use Illuminate\Http\Request;

class CourseFeedbackController extends Controller
{
    public function index(){

        $coursefeedbacks=CourseFeedback::all();
        
        if ($coursefeedbacks->isEmpty()) {
            return response([
                'message' => 'No course feedbacks found',
            ], 404); 
        }

        return response([
            'message'=>'course feedbacks retreived successfully',
            'data'=>$coursefeedbacks
        ],200);



    }



    public function store(Request $request){
        $user=auth()->user();
        $request->validate([
            'course_id' => 'required',
            'rating' => 'required|integer|min:0|max:5',
            'comment' => 'nullable|string|max:500',
        ]);


        $feedback = CourseFeedback::create([
            'course_id' => $request->course_id,
            'student_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response(['message' => 'feedback created successfully', 'data' => $feedback],201);
    }



    
    public function destroy($id){
        $user=auth()->user();

        $coursefeedback=CourseFeedback::find($id);
        if(!$coursefeedback){
            return response([
                'message'=>'coursefeedback not found'
            ],404);
        }

        if($coursefeedback->student_id!=$user->id){
            return response(['message'=>'this is not your feedback to delete'],400);
        }

        $coursefeedback->delete();
        return response([
            'message'=>'coursefeedback deleted successfully',
        ],200);
    }
}
