<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product in the cart.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the total price for this cart item.
     */
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
