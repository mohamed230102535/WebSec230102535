<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    // Order Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
    
    /**
     * Get available next statuses based on current status
     */
    public function getNextStatuses()
    {
        $allStatuses = self::getStatuses();
        
        switch($this->status) {
            case self::STATUS_PENDING:
                return [
                    self::STATUS_PROCESSING => $allStatuses[self::STATUS_PROCESSING],
                    self::STATUS_CANCELLED => $allStatuses[self::STATUS_CANCELLED],
                ];
            case self::STATUS_PROCESSING:
                return [
                    self::STATUS_SHIPPED => $allStatuses[self::STATUS_SHIPPED],
                    self::STATUS_CANCELLED => $allStatuses[self::STATUS_CANCELLED],
                ];
            case self::STATUS_SHIPPED:
                return [
                    self::STATUS_DELIVERED => $allStatuses[self::STATUS_DELIVERED],
                    self::STATUS_CANCELLED => $allStatuses[self::STATUS_CANCELLED],
                ];
            case self::STATUS_DELIVERED:
                return [];
            case self::STATUS_CANCELLED:
                return [];
            default:
                return $allStatuses;
        }
    }

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
