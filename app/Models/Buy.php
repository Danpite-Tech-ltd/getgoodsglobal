<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    use HasFactory;
    protected $table = 'buy';
    protected $guarded=[];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function buydetails()
    {
        return $this->hasMany(BuyDetail::class, 'buy_id');
    }
}
