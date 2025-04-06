<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class UsersDB extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'onehitpoint';
    protected $fillable = ['name', 'email', 'password'];

    public function quizzes()
    {
        return $this->hasMany(quiz::class, 'created_by');
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'user_id');
    }
}
