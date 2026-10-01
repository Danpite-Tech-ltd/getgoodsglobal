<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shape extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'input_list', 'status'];

    protected $casts = [
        'input_list' => 'array',
    ];
}
