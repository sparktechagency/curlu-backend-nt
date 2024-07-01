<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'email','amount','description','due_date','invoice_number','paid','link','stripe_payment_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storeData($data) {
        return static::create($data);
    }

    public function updateData($input, $slug) {
        return static::where('link', $slug)->update($input);
    }
}
