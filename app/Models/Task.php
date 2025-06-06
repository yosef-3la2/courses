<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
         'description',
         'course_id',
         'instructor_id',
         'session_id',
         'deadline',
        ];
    protected $hidden = ['created_at', 'updated_at'];
}
