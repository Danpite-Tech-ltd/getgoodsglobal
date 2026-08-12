<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomeThana extends Model
{
    use HasFactory;
    public function district()
    {
        return $this->hasOne(CustomeDistrict::class,'id','district_id');
    }
}
