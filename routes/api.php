<?php

use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\SessionController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// i can view courses or one course by id if im not auhtenticated
Route::prefix('courses/')->group(function(){
    route::get('',[CourseController::class,'index']);
    route::get('/{id}',[CourseController::class,'show']);
});



//login page
Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout',[UserController::class,'logout']);
    Route::post('updateprofile',[UserController::class],'updateprofile');

    Route::middleware('Admin')->prefix('courses/')->group(function(){
        route::delete('/{id}',[CourseController::class,'destroy']);
        route::post('',[CourseController::class,'store']);
        route::post('/{id}',[CourseController::class,'update']);
    });

    Route::prefix('sessions/')->group(function(){
        route::get('',[SessionController::class,'index']);
        route::post('',[SessionController::class,'store']);
        route::post('/{id}',[SessionController::class,'update']);
        route::delete('/{id}',[SessionController::class,'destroy']);
    });

    Route::prefix('tasks/')->group(function(){
        route::get('',[TaskController::class,'index']);
        route::post('',[TaskController::class,'store']);
        route::post('/{id}',[TaskController::class,'update']);
        route::delete('/{id}',[TaskController::class,'destroy']);
    });

});