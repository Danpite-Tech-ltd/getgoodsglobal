<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResinRatio extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'resin', 'hardener', 'resin_density', 'hardener_density', 'wastage', 'status'];
}
