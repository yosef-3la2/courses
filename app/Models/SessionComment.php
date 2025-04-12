<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionComment extends Model
{
    protected $table = 'sessioncomments';
    protected $fillable = ['session_id', 'student_id', 'comment'];  
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory; 
}
