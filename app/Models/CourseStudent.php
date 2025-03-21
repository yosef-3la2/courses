<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    protected $table = 'course_student';
    protected $fillable=[
        'name',
        'course_id',
        'student_id',
        'instructor_id'
    ];
    
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;
}
