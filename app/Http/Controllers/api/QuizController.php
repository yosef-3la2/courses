<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\CourseStudent;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('instructor')->except(['index']);
    }


    public function index(){

        
        $user=auth()->user();
        if($user->role=='admin'){
            $quizzes=Quiz::get();
        }elseif($user->role=='instructor'){
            $quizzes=Quiz::where('instructor_id',$user->id)->get();
        }else{
            $coursestudent=CourseStudent::where('student_id',$user->id)->get();
            if($coursestudent->isEmpty()){
                return response([
                    'message'=>'you didnt enroll in any course ',
                    'data'=>null
                ],400);  
            }

            $courseIds = $coursestudent->pluck('course_id')->toArray();
            $instructorIds = $coursestudent->pluck('instructor_id')->toArray();
            $quizzes=Quiz::wherein('course_id',$courseIds)->wherein('instructor_id', $instructorIds)->get();
        }
        if($quizzes->isEmpty()){
            if($user->role=='admin'){

                return response([
                    'message'=>'NO quizzes found '
                ],400);  
            }
            return response([
                'message'=>'you have no quizzes '
            ],400);  
        }
        
        
        
        return response([
            'message'=>'quizzes retreived successfully',
            'data'=>$quizzes
        ],200);

        

    }




    public function store(Request $request){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'about'=>'required|string|min:2',
            'course_id'=>'required|integer',
            'quiz_datetime'=>'required|date|after:now',
            'duration'=>'required|integer',
            'link'=>'required|url'
            
        ]);

        $user = auth()->user();
        if(!$user||$user->role!=='instructor'){
            return response(['message'=>'unauthorized'],403 );
        }
        //checking if the course exists and the instructor is teaching the course
        $courseid=$request->course_id;
        $instructor_id=$user->id;

        //checking if the course exists
        $course=Course::find($courseid);
        if(!$course){
            return response(['mesaage'=>'course not found'],404);
        }


        $instructor_is_assigned=CourseInstructor::where('course_id',$courseid)->where('instructor_id',$instructor_id)->exists();
        if(!$instructor_is_assigned){

            return response(['message'=>'your are not teaching this course please make sure you have chosen the right course'],400);
        }



        $Quiz=Quiz::create([
            'title'=>$request->title,
            'about'=>$request->about,
            'course_id'=>$request->course_id,
            'duration'=>$request->duration,
            'instructor_id'=>$user->id,
            'quiz_datetime'=>$request->quiz_datetime,
            'link'=>$request->link,
            
        ]);

        return response([
            'message'=>'Quiz uploaded successfully',
            'data'=>$Quiz
        ],201);
    }


    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'about'=>'required|string|min:2',
            'course_id'=>'required|integer',
            'quiz_datetime'=>'required|date|after:now',
            'duration'=>'required|integer',
            'link'=>'required|url'
            
        ]);

        $Quiz=Quiz::find($id);
        $user=auth()->user();



        if(!$Quiz){
            return response([
                'message'=>'Quiz not found'
            ],404);
        }


        $courseid=$request->course_id;
        $instructor_id=$user->id;
        $instructor_is_assigned=CourseInstructor::where('course_id',$courseid)->where('instructor_id',$instructor_id)->exists();
        
        //checking if the course exists
        $course=Course::find($courseid);
        if(!$course){
            return response(['mesaage'=>'course not found'],404);
        }
        //checking if the instructor teaches the chosen course
        if(!$instructor_is_assigned){
            
            return response(['message'=>'your are not teaching this course please make sure you have chosen the right course'],400);
        }


        

        $Quiz->update([
            'title'=>$request->title,
            'about'=>$request->about,
            'course_id'=>$request->course_id,
            'duration'=>$request->duration,
            'instructor_id'=>$user->id,
            'quiz_datetime'=>$request->quiz_datetime,
            'link'=>$request->link,
        ]);
        return response([
            'message'=>'Quiz updated successfully',
            'data'=>$Quiz
        ],200);
    }


    public function destroy($id){
        $Quiz=Quiz::find($id);
        $user=auth()->user();


        if(!$Quiz){
            return response([
                'message'=>'Quiz not found'
            ],404);
        }
        if ($Quiz->instructor_id !== $user->id) {
            return response(['message' => 'forbidden - you do not own this Quiz'], 403);
        }
        $Quiz->delete();
        return response([
            'message'=>'Quiz deleted successfully',
        ],200);
    }
}

