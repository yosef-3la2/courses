<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'course_id',
        'link',
        'instructor_id'
];
    protected $hidden = ['created_at', 'updated_at'];
}
