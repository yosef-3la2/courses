<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFeedback extends Model
{
    protected $table = 'coursefeedback';
    
    protected $fillable = ['course_id', 'student_id', 'rating', 'comment'];
    
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;
}
