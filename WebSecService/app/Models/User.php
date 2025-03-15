<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use Notifiable;
    use HasRoles;


    protected $table = 'onehitpoint'; 

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

}
