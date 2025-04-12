<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorFeedback extends Model
{
    protected $table = 'instructorfeedback';
    protected $fillable = ['instructor_id', 'student_id', 'rating', 'comment'];
    
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;
}
