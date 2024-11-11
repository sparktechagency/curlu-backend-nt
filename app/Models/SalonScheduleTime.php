<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonScheduleTime extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'schedule' => 'json'
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
