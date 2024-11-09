<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function salon(){
        return $this->belongsTo(Salon::class);
    }

    public function payment_detail(){
        return $this->belongsTo(PaymentDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
