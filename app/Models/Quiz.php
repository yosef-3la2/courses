<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'title',
         'about',
         'course_id',
         'instructor_id',
         'quiz_datetime',
         'duration',
         'link',
        ];
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;
}
