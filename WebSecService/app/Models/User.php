<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable {
    use Notifiable;
    use HasRoles;


    protected $connection = 'mysql'; // Ensure this matches your database.php config
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
