<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable {
    use HasFactory, Notifiable, HasRoles;


    protected $connection = 'mysql'; // Ensure this matches your database.php config
    protected $table = 'onehitpoint';
    protected $fillable = [
        'name',
        'email',
        'password',
        'credit'
    ];

    public function quizzes()
    {
        return $this->hasMany(quiz::class, 'created_by');
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'user_id');
    }

}
