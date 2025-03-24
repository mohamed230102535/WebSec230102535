<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $table = 'products'; 
    
    protected $fillable = [
        'code', 
        'name', 
        'model', 
        'photo', 
        'description', 
        'price', 
        'stock'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];
}
