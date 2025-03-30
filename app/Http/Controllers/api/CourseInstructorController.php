<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseInstructor;
use App\Models\User;
use Illuminate\Http\Request;

class CourseInstructorController extends Controller
{
    public function index(){
        $courseInstructor=CourseInstructor::all();
        
        return response([
            'message'=>'courseInstrucor retreived successfully',
            'data'=>$courseInstructor
        ],200);
        

    }



    public function show($id){
        $courseInstructor=CourseInstructor::find($id);
        if(!$courseInstructor){
            return response([
                'message'=>'courseInstructor not found',
                
            ],404);
        }
        return response([
            'message'=>'courseInstructor retreived successfully',
            'data'=>$courseInstructor
        ],200);

    }



    public function store(Request $request){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'course_id'=>'required|integer|exists:courses,id',
            'instructor_id'=>'required|integer|exists:users,id'
        ]);

        $userid=$request->instructor_id;
        $instructor=User::find($userid);
        if($instructor->role!=='instructor'){
            return response([
                'message'=> 'this user is not an instructor'
            ],400);
        }


        //making sure that instructor cant be assigned to the same course twice
        $course_id=$request->course_id;
        $instructor_id=$request->instructor_id;
        $instructor_is_assigned=CourseInstructor::where('course_id',$course_id)->where('instructor_id',$instructor_id)->first();
        if($instructor_is_assigned){
            return response([
                'message'=>'this instructor is already assigned to this course',
                'data'=>$instructor_is_assigned
            ],400);
        }
        $courseInstructor=CourseInstructor::create([
            'title'=>$request->title,
            'course_id'=>$request->course_id,
            'instructor_id'=>$request->instructor_id
        ]);

        return response([
            'message'=>'course created successfully',
            'data'=>$courseInstructor
        ],201);
    }


    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'course_id'=>'required|integer|exists:courses,id',
            'instructor_id'=>'required|integer|exists:users,id'
        ]);


        $userid=$request->instructor_id;
        $instructor=User::find($userid);
        if($instructor->role!=='instructor'){
            return response([
                'message'=> 'this user is not an instructor'
            ],400);
        }

        $courseInstructor=CourseInstructor::find($id);

        if(!$courseInstructor){
            return response([
                'message'=>'courseInstructor not found'
            ],404);
        }

        //making sure that instructor cant be assigned to the same course twice
        $course_id=$request->course_id;
        $instructor_id=$request->instructor_id;
        $instructor_is_assigned=CourseInstructor::where('course_id',$course_id)->where('instructor_id',$instructor_id)->first();
        
        if($course_id==$courseInstructor->course_id && $instructor_id==$courseInstructor->instructor_id && $request->title==$courseInstructor->title){
            return response([
                'message'=>'no updates done',
                'data'=>$courseInstructor
            ],400);
        }
        
        if($instructor_is_assigned&& $request->title==$courseInstructor->title){
            return response([
                'message'=>'this instructor is already assigned to this course',
                'data'=>$instructor_is_assigned
            ],400);
        }

        $courseInstructor->update([
            'title'=>$request->title,
            'course_id'=>$request->course_id,
            'instructor_id'=>$request->instructor_id
        ]);
        return response([
            'message'=>'courseInstructor updated successfully',
            'data'=>$courseInstructor
        ],200);
    }


    public function destroy($id){
        $courseInstructor=CourseInstructor::find($id);
        if(!$courseInstructor){
            return response([
                'message'=>'courseInstructor not found'
            ],404);
        }
        $courseInstructor->delete();
        return response([
            'message'=>'courseInstructor deleted successfully',
        ],200);
    }
}
