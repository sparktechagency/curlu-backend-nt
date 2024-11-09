<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalonService extends Model
{
    use HasFactory;

    public function salon():BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
   public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
