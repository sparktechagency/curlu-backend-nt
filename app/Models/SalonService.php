<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalonService extends Model
{
    use HasFactory;
    // protected $fillable = ['wishlist'];
    protected $guarded=['id'];

    public function salon():BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist(){
        return $this->belongsTo(ServiceWishlist::class);
    }


}
