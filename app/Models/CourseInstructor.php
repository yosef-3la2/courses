<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInstructor extends Model
{
    protected $table = 'course_instructor';
    protected $fillable=[
        'title',
        'course_id',
        'instructor_id'
];

protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;
}
