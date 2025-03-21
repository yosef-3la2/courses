<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\Request;
use Mockery\Matcher\Subset;

class SubmissionController extends Controller
{
    public function __construct()
    {

    $this->middleware('student')->except(['index']);
    $this->middleware('StudentOrInstructor')->only(['index']);

    }


    public function index(){

        
        $user=auth()->user();
        if($user->role=='student'){
            $submissions=Submission::where('student_id',$user->id)->get();
        }elseif($user->role=='instructor')
        {
           $tasks=Task::where('instructor_id',$user->id);
           $taskids=$tasks->pluck('id')->toArray();
           if(empty($taskids)){
            return response(['message'=>'You have no tasks so there is no submissions'],404);
           }
           $submissions=Submission::wherein('task_id',$taskids)->get();

            
        }
        if($submissions->isEmpty()){
            
            return response([
                'message'=>'you have no submissions '
            ],400);  
        }
        
        
        return response([
            'message'=>'submissions retreived successfully',
            'data'=>$submissions
        ],200);

        

    }




    public function store(Request $request){

        
        $filesize=5*1024;
        $request->validate([
            'task_id'=>'required|integer',
            'file'=>"nullable|file|mimes:pdf,doc,docx,txt,py,php,js,java,cpp,c,cs,html,css,zip,rar,xlsx,csv,jpg,jpeg,png|max:$filesize",
            'link'=>'nullable|url'
            
        ]);

        //student cant create a more than one submission to the same task
        $user = auth()->user();
        $oldSubmission = Submission::where('task_id', $request->task_id)->where('student_id', $user->id)->first();
        if($oldSubmission){
            return response([
                'message'=>'you have already submitted your answer to this task if u want to create a new submission update it or delete it'
            ],400);
        }
        
        //uploading file
        if($request->hasFile('file')){
            $filedata=$request->file('file');
            $filerealname=$filedata->getClientOriginalName();
            $filename="Courses_web".rand(0,300000)."_".time()."_".$filerealname;
            $location=public_path('/assets/files');
            $filedata->move($location,$filename);
            $file=$filename;
        }else{
            $file=null;
        }
        
        
        

        
        $link=$request->link;
        if(!$request->file('file') && !$link){
            return response([
                'message'=>'One of the fields is required'
            ],400);
        }




        $task=Task::where('id',$request->task_id)->first();
        if(!$task){
            return response(['message'=>'task not found'],404);
        }



        
        $submission=Submission::create([
            'task_id'=>$request->task_id,
            'student_id'=>$user->id,
            'file'=>$file,
            'link'=>$request->link,
        ]);
        
        return response([
            'message'=>'submission uploaded successfully',
            'data'=>$submission
        ],200);

    }


    public function update(Request $request,$id){
        $filesize=5*1024;
        $request->validate([
            'file'=>"nullable|file|mimes:pdf,doc,docx,txt,py,php,js,java,cpp,c,cs,html,css,zip,rar,xlsx,csv,jpg,jpeg,png|max:$filesize",
            'link'=>'nullable|url'
            
        ]);

        
        $submission=Submission::find($id);
        if(!$submission){
            return response(['message'=>'submission not found'],404);
        }
        //uploading file
        if($request->hasFile('file')){
            $filedata=$request->file('file');
            $filerealname=$filedata->getClientOriginalName();
            $filename="Courses_web".rand(0,300000)."_".time()."_".$filerealname;
            $location=public_path('/assets/files');
            $filedata->move($location,$filename);
            $file=$filename;
            $oldfilepath=public_path("/assets/files/$submission->file");
            if(file_exists($oldfilepath)){
                unlink($oldfilepath);
            }
        }else{
            $file=$submission->file;
        }
        
        
        

        
        $link=$request->link;
        if(!$request->hasfile('file') && !$link){
            return response([
                'message'=>'One of the fields is required'
            ],400);
        }




        
        
        
        $user = auth()->user();


        if($submission->student_id!==$user->id){
            return response(['message'=>'this is not ur submission , u cant edit it'],403);
        }



        $task_id=$submission->task_id;
        $submission->update([
            'task_id'=>$task_id,
            'student_id'=>$user->id,
            'file'=>$file,
            'link'=>$request->link,
        ]);
        
        return response([
            'message'=>'submission has been updated successfully',
            'data'=>$submission
        ],200);

    }


    public function destroy($id){
        $submission=submission::find($id);
        $user=auth()->user();


        if(!$submission){
            return response([
                'message'=>'submission not found'
            ],404);
        }
        if ($submission->student_id !== $user->id) {
            return response(['message' => 'forbidden - you cant delete this submission'], 403);
        }

        if($submission->file){
            $filepath=public_path("assets/files/$submission->file");
            if(file_exists($filepath)){
                unlink($filepath);
            }
        }

        $submission->delete();
        return response([
            'message'=>'submission deleted successfully',
        ],200);
    }
}
