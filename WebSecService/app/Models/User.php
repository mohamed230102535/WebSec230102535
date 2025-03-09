<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $table = 'userscrud'; // Set the correct table name

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $casts = [
        'role' => 'string', 
    ];
    
}
