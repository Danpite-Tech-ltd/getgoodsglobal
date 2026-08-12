<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productcolor extends Model
{
    use HasFactory;
    public function color(){
        return $this->hasOne('App\Models\Color', 'id', 'color_id');
    }
    
public function sizes()
{
    return $this->hasMany(Productsize::class, 'color_id', 'color_id')
                ->whereColumn('product_id', 'productcolors.product_id');
}

}
