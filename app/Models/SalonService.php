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
<<<<<<< HEAD
   public function category():BelongsTo
    {
=======

    public function category(){
>>>>>>> 422e66558673a397c9f3b94c9792aeb4ef450233
        return $this->belongsTo(Category::class);
    }
}
