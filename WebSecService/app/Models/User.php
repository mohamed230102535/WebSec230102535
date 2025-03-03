<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
    
    protected $table = 'userscrud'; // Set the correct table name

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $casts = [
        'role' => 'string', // Ensure role is treated as a string
    ];
    
}
