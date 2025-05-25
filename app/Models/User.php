<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Purchase;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'credit',
        'github_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ms_graph_expires' => 'datetime',
        ];
    }

    /**
     * Get the purchases for the user.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
    
    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    /**
     * Get the reviews written by the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Check if the user has purchased a specific product.
     */
    public function hasPurchased($productId)
    {
        return $this->orders()
            ->whereHas('items', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
}
