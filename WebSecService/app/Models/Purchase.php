<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Purchase extends Model {
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'purchased_at'
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    public $timestamps = true;

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
