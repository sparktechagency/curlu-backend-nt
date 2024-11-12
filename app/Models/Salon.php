<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Salon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'experience',
        'salon_type',
        'salon_description',
        'id_card',
        'iban_number',
        'kbis',
    ];
//    protected $table = 'salons';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salon_services(): HasMany
    {
        return $this->hasMany(SalonService::class);
    }

    public function salon_schedule_time(): HasOne
    {
        return $this->hasOne(SalonScheduleTime::class);
    }
    public function salon_invoices(): HasMany
    {
        return $this->hasMany(SalonInvoice::class);
    }

}
