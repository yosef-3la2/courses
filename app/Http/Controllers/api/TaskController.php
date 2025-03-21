<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\CourseStudent;
use App\Models\Session;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('instructor')->except(['index']);
    }


    public function index(){

        
        $user=auth()->user();
        if($user->role=='admin'){
            $tasks=Task::get();
        }elseif($user->role=='instructor'){
            $tasks=task::where('instructor_id',$user->id)->get();
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
            $tasks=task::wherein('course_id',$courseIds)->wherein('instructor_id', $instructorIds)->get();
        }
        if($tasks->isEmpty()){
            if($user->role=='admin'){

                return response([
                    'message'=>'NO tasks found '
                ],400);  
            }
            return response([
                'message'=>'you have no tasks '
            ],400);  
        }
        
        
        
        return response([
            'message'=>'tasks retreived successfully',
            'data'=>$tasks
        ],200);

        

    }




    public function store(Request $request){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'description'=>'required|string|min:2',
            'course_id'=>'required|integer',
            'session_id'=>'nullable|integer',
            'deadline'=>'nullable|date|after:now'
            
        ]);

        $user = auth()->user();
        if(!$user||$user->role!=='instructor'){
            return response(['message'=>'unauthorized'],403 );
        }
        //checking if the course exists and the instructor is taechung the course
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


        //checking if the session exists and the instructor created this session
       
        
        if($request->session_id){

            $session=Session::where('id',$request->session_id)->first();
            if(!$session){
                return response(['message'=>'the session is not found'],404);
            }
            if($session->instructor_id!==$user->id){
                return response(['message'=>'forbidden - you do not own this session'],403);
            }
            if($session->course_id!==$courseid){
                return response(['message'=>'this session doesnt belong to the choosen course'],400);
            }
        }

        $task=task::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'course_id'=>$request->course_id,
            'session_id'=>$request->session_id,
            'instructor_id'=>$user->id,
            'deadline'=>$request->deadline,
            
        ]);

        return response([
            'message'=>'task uploaded successfully',
            'data'=>$task
        ],201);
    }


    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'description'=>'required|string|min:2',
            'course_id'=>'required|integer',
            'session_id'=>'nullable|integer',
            'deadline'=>'nullable|date|after:now'
        ]);

        $task=task::find($id);
        $user=auth()->user();



        if(!$task){
            return response([
                'message'=>'task not found'
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


        if($request->session_id){

            $session=Session::find($request->session_id);
            if(!$session){
                return response(['message'=>'the session is not found'],404);
            }
            if($session->instructor_id!=$user->id){
                return response(['message'=>'forbidden - you do not own this session'],403);
            }
            if($session->course_id!=$courseid){
                return response(['message'=>'this session doesnt belong to the choosen course'],400);
            }
        }

        $task->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'course_id'=>$request->course_id,
            'session_id'=>$request->session_id,
            'instructor_id'=>$user->id,
            'deadline'=>$request->deadline,
        ]);
        return response([
            'message'=>'task updated successfully',
            'data'=>$task
        ],200);
    }


    public function destroy($id){
        $task=task::find($id);
        $user=auth()->user();


        if(!$task){
            return response([
                'message'=>'task not found'
            ],404);
        }
        if ($task->instructor_id !== $user->id) {
            return response(['message' => 'forbidden - you do not own this task'], 403);
        }
        $task->delete();
        return response([
            'message'=>'task deleted successfully',
        ],200);
    }
}
