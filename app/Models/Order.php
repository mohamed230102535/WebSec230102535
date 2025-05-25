<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'delivery_method',
        'shipping_address',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $orderNumber = $prefix . time() . '-' . rand(1000, 9999);
        
        // Ensure the order number is unique
        while (self::where('order_number', $orderNumber)->exists()) {
            $orderNumber = $prefix . time() . '-' . rand(1000, 9999);
        }
        
        return $orderNumber;
    }
}
