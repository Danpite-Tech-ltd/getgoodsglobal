<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    public function ticketdetails()
    {
        return $this->hasMany(Ticketdetails::class, 'tkt_id');
    }
    
    public function ticketdetailsmessage()
    {
        return $this->hasMany(Ticketdetails::class, 'tkt_id')->orderBy('id','DESC');
    }
    
    protected $casts = [
        'closed_date' => 'datetime',
    ];
}
