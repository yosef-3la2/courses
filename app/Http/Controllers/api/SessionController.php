<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseInstructor;
use App\Models\CourseStudent;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('instructor')->except(['index']);
    }


    public function index(){

        
        $user=auth()->user();
        if($user->role=='admin'){
            $sessions=Session::get();
        }elseif($user->role=='instructor'){
            $sessions=Session::where('instructor_id',$user->id)->get();
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
            $sessions=Session::wherein('course_id',$courseIds)->wherein('instructor_id', $instructorIds)->get();
        }
        if($sessions->isEmpty()){
            return response([
                'message'=>'you have no sessions '
            ],400);  
        }
        
        
        
        return response([
            'message'=>'sessions retreived successfully',
            'data'=>$sessions
        ],200);

        

    }




    public function store(Request $request){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'course_id'=>'required|integer|exists:courses,id',
            'link'=>'required|url'
        ]);

        $user = auth()->user();
        if(!$user||$user->role!=='instructor'){
            return response(['message'=>'unauthorized'],403 );
        }

        $courseid=$request->course_id;
        $instructor_id=$user->id;
        $instructor_is_assigned=CourseInstructor::where('course_id',$courseid)->where('instructor_id',$instructor_id)->exists();
        if(!$instructor_is_assigned){

            return response(['message'=>'your are not teaching this course please make sure you have chosen the right course'],400);
        }



        $session=Session::create([
            'title'=>$request->title,
            'course_id'=>$request->course_id,
            'instructor_id'=>$user->id,
            'link'=>$request->link
        ]);

        return response([
            'message'=>'session uploaded successfully',
            'data'=>$session
        ],201);
    }


    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'course_id'=>'required|integer|exists:courses,id',
            'link'=>'required|url'
        ]);

        $session=Session::find($id);
        $user=auth()->user();

        if(!$session){
            return response([
                'message'=>'session not found'
            ],404);
        }

        if ($session->instructor_id !== $user->id) {
            return response(['message' => 'forbidden - you do not own this session'], 403);
        }

        $session->update([
            'title'=>$request->title,
            'course_id'=>$request->course_id,
            'link'=>$request->link
        ]);
        return response([
            'message'=>'session updated successfully',
            'data'=>$session
        ],200);
    }


    public function destroy($id){
        $session=Session::find($id);
        $user=auth()->user();


        if(!$session){
            return response([
                'message'=>'session not found'
            ],404);
        }
        if ($session->instructor_id !== $user->id) {
            return response(['message' => 'forbidden - you do not own this session'], 403);
        }
        $session->delete();
        return response([
            'message'=>'session deleted successfully',
        ],200);
    }
}
