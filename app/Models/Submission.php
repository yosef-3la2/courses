<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
         'student_id',
         'file',
         'link',
        ];
    protected $hidden = ['created_at', 'updated_at'];
}
