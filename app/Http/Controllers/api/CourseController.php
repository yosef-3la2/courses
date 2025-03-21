<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(){
        $courses=Course::all();
        
        return response([
            'message'=>'courses retreived successfully',
            'data'=>$courses
        ],200);

    }



    public function show($id){
        $course=Course::find($id);
        if(!$course){
            return response([
                'message'=>'course not found',
                
            ],404);
        }
        return response([
            'message'=>'course retreived successfully',
            'data'=>$course
        ],200);

    }



    public function store(Request $request){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'description'=>'required|string|min:5',
            'price'=>'numeric|required'
        ]);

        $course=Course::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'price'=>$request->price
        ]);

        return response([
            'message'=>'course created successfully',
            'data'=>$course
        ],201);
    }


    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|string|max:20|min:2',
            'description'=>'required|string|min:5',
            'price'=>'numeric|required'
        ]);

        $course=Course::find($id);

        if(!$course){
            return response([
                'message'=>'course not found'
            ],404);
        }
        $course->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'price'=>$request->price
        ]);
        return response([
            'message'=>'course updated successfully',
            'data'=>$course
        ],200);
    }


    public function destroy($id){
        $course=Course::find($id);
        if(!$course){
            return response([
                'message'=>'course not found'
            ],404);
        }
        $course->delete();
        return response([
            'message'=>'course deleted successfully',
        ],200);
    }
}
