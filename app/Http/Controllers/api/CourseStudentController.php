<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseStudent;
use App\Models\User;
use Illuminate\Http\Request;

class CourseStudentController extends Controller
{
    public function index(){
        $coursestudent=CourseStudent::all();
        
        return response([
            'message'=>'coursestudent retreived successfully',
            'data'=>$coursestudent
        ],200);
        

    }



    public function show($id){
        $coursestudent=CourseStudent::where('student_id',$id)->get();
        if(!$coursestudent){
            return response([
                'message'=>'coursestudent not found',
                
            ],404);
        }
        return response([
            'message'=>'coursestudent retreived successfully',
            'data'=>$coursestudent
        ],200);

    }



    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:20|min:2',
            'course_id'=>'required|integer|exists:courses,id',
            'student_id'=>'required|integer|exists:users,id',
            'instructor_id'=>'required|integer|exists:users,id',
        ]);

        $studentid=$request->student_id;
        $student=User::find($studentid);
        if($student->role!=='student'){
            return response([
                'message'=> 'this user is not an student'
            ],400);
        }
        $instructorid=$request->instructor_id;
        $instructor=User::find($instructorid);
        if($instructor->role!=='instructor'){
            return response([
                'message'=> 'this user is not an instructor'
            ],400);
        }

       

        $coursestudent=CourseStudent::create([
            'name'=>$request->name,
            'course_id'=>$request->course_id,
            'instructor_id'=>$request->instructor_id,
            'student_id'=>$request->student_id
        ]);

        return response([
            'message'=>'course created successfully',
            'data'=>$coursestudent
        ],201);
    }


    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required|string|max:20|min:2',
            'course_id'=>'required|integer|exists:courses,id',
            'student_id'=>'required|integer|exists:users,id',
            'instructor_id'=>'required|integer|exists:users,id',
        ]);


        $studentid=$request->student_id;
        $student=User::find($studentid);
        if($student->role!=='student'){
            return response([
                'message'=> 'this user is not an student'
            ],400);
        }
        $instructorid=$request->instructor_id;
        $instructor=User::find($instructorid);
        if($instructor->role!=='instructor'){
            return response([
                'message'=> 'this user is not an instructor'
            ],400);
        }

        $coursestudent=CourseStudent::find($id);

        if(!$coursestudent){
            return response([
                'message'=>'coursestudent not found'
            ],404);
        }
        $coursestudent->update([
            'name'=>$request->name,
            'course_id'=>$request->course_id,
            'instructor_id'=>$request->instructor_id,
            'student_id'=>$request->student_id
        ]);
        return response([
            'message'=>'coursestudent updated successfully',
            'data'=>$coursestudent
        ],200);
    }


    public function destroy($id){
        $coursestudent=CourseStudent::find($id);
        if(!$coursestudent){
            return response([
                'message'=>'coursestudent not found'
            ],404);
        }
        $coursestudent->delete();
        return response([
            'message'=>'coursestudent deleted successfully',
        ],200);
    }
}
