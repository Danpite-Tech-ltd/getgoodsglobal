<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->select('id', 'invoice_id', 'paid_partial_payment_amount', 'order_status', 'pay_slip_image');
    }
}
