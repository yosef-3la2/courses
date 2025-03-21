<?php

use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\CourseInstructorController;
use App\Http\Controllers\api\CourseStudentController;
use App\Http\Controllers\api\SessionController;
use App\Http\Controllers\api\SubmissionController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;



// i can view courses or one course by id if im not auhtenticated
Route::prefix('courses/')->group(function(){
    route::get('',[CourseController::class,'index']);
    route::get('/{id}',[CourseController::class,'show']);
});



//login page
Route::post('login',[UserController::class,'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout',[UserController::class,'logout']);

    //registeration 
    Route::middleware('Admin')->group(function(){
        Route::post('register',[UserController::class,'register']);
        Route::get('showadmins',[UserController::class,'showadmins']);
        Route::get('showstudents',[UserController::class,'showstudents']);
        Route::get('showinstructors',[UserController::class,'showinstructors']);
    
    });

    //courses
    Route::middleware('Admin')->prefix('courses/')->group(function(){
        route::delete('/{id}',[CourseController::class,'destroy']);
        route::post('',[CourseController::class,'store']);
        route::post('/{id}',[CourseController::class,'update']);
    });

    //courseinstructor
    Route::middleware('Admin')->prefix('courseinstructor/')->group(function(){
        route::get('',[CourseInstructorController::class,'index']);
        route::get('/{id}',[CourseInstructorController::class,'index']);
        route::post('',[CourseInstructorController::class,'store']);
        route::post('/{id}',[CourseInstructorController::class,'update']);
        route::delete('/{id}',[CourseInstructorController::class,'destroy']);
    });

    //coursestudent
    Route::middleware('Admin')->prefix('coursestudent/')->group(function(){
        route::get('',[CourseStudentController::class,'index']);
        route::get('/{id}',[CourseStudentController::class,'show']);
        route::post('',[CourseStudentController::class,'store']);
        route::post('/{id}',[CourseStudentController::class,'update']);
        route::delete('/{id}',[CourseStudentController::class,'destroy']);
    });



    //sessions
    Route::prefix('sessions/')->group(function(){
        route::get('',[SessionController::class,'index']);
        route::post('',[SessionController::class,'store']);
        route::post('/{id}',[SessionController::class,'update']);
        route::delete('/{id}',[SessionController::class,'destroy']);
    });



    //tasks
    Route::prefix('tasks/')->group(function(){
        route::get('',[TaskController::class,'index']);
        route::post('',[TaskController::class,'store']);
        route::post('/{id}',[TaskController::class,'update']);
        route::delete('/{id}',[TaskController::class,'destroy']);
    });



    //submissions
    Route::prefix('submissions/')->group(function(){
        route::get('',[SubmissionController::class,'index']);
        route::post('',[SubmissionController::class,'store']);
        route::post('/{id}',[SubmissionController::class,'update']);
        route::delete('/{id}',[SubmissionController::class,'destroy']);
    });

});