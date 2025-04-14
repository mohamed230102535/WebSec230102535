<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Purchase;

class Product extends Model  {
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'price',
        'model',
        'description',
        'photo',
        'stock'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}