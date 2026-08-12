<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = "blogs";
    protected $fillable = [
        'b_title',
        'b_short_des',
        'b_long_des',
        'b_image',
        'b_author',
        'b_author_image',
        'b_date',
        'status',
    ];




}
