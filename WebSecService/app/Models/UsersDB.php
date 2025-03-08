<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UsersDB extends Model {
    protected $table = 'usersdb';

    protected $fillable = [
        'fullname',
        'email',
        'password'
    ];

    
}