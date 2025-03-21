<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable=[
        'title',
        'description',
        'price'
    ];
    
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;
}
