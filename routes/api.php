<?php

use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\CourseFeedbackController;
use App\Http\Controllers\api\CourseInstructorController;
use App\Http\Controllers\api\CourseStudentController;
use App\Http\Controllers\api\InstructorFeedbackController;
use App\Http\Controllers\api\QuizController;
use App\Http\Controllers\api\SessionCommentController;
use App\Http\Controllers\api\SessionController;
use App\Http\Controllers\api\SubmissionController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserController;
use App\Models\InstructorFeedback;
use Illuminate\Support\Facades\Route;



// i can view courses or one course by id if im not auhtenticated
Route::prefix('courses/')->group(function(){
    route::get('',[CourseController::class,'index']);
    route::get('/{id}',[CourseController::class,'show']);
});

// i can view course feedbacks if im not auhtenticated
Route::prefix('instructorfeedback/')->group(function(){
route::get('',[InstructorFeedbackController::class,'index']);
});

//login page
Route::post('login',[UserController::class,'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout',[UserController::class,'logout']);
    Route::post('update ',[UserController::class,'update']);

    //registeration 
    Route::middleware('Admin')->group(function(){
        Route::post('register',[UserController::class,'register']);
        Route::get('showadmins',[UserController::class,'showadmins']);
        Route::get('showstudents',[UserController::class,'showstudents']);
        Route::get('showinstructors',[UserController::class,'showinstructors']);
        Route::get('showallusers',[UserController::class,'showallusers']);
        Route::delete('deleteaccount/{id}',[UserController::class,'deleteaccount']);
    
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
        route::get('/{id}',[TaskController::class,'show']);
        route::post('/{id}',[TaskController::class,'update']);
        route::delete('/{id}',[TaskController::class,'destroy']);
    });



    //quizzes
    Route::prefix('quizzes/')->group(function(){
        route::get('',[QuizController::class,'index']);
        route::post('',[QuizController::class,'store']);
        route::post('/{id}',[QuizController::class,'update']);
        route::delete('/{id}',[QuizController::class,'destroy']);
    });



    //submissions
    Route::prefix('submissions/')->group(function(){
        route::get('',[SubmissionController::class,'index']);
        route::post('',[SubmissionController::class,'store']);
        route::post('/{id}',[SubmissionController::class,'update']);
        route::delete('/{id}',[SubmissionController::class,'destroy']);
    });



    //instructor feedback
    Route::prefix('instructorfeedback/')->group(function(){
        route::get('',[InstructorFeedbackController::class,'index']);
        route::post('',[InstructorFeedbackController::class,'store'])->middleware('student');
        route::delete('/{id}',[InstructorFeedbackController::class,'destroy'])->middleware('student');
    });


    //course feedback
    Route::prefix('coursefeedback/')->group(function(){
        route::get('',[CourseFeedbackController::class,'index']);
        route::post('',[CourseFeedbackController::class,'store'])->middleware('student');
        route::delete('/{id}',[CourseFeedbackController::class,'destroy'])->middleware('student');
    });


    //session comment
    Route::prefix('sessioncomment/')->group(function(){
        route::get('',[SessionCommentController::class,'index']);
        route::post('',[SessionCommentController::class,'store'])->middleware('StudentOrInstructor');
        route::delete('/{id}',[SessionCommentController::class,'destroy'])->middleware('StudentOrInstructor');
    });
        

});