<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\question;
use App\Models\User;
use App\Models\choices;
class UserAnswer extends Model {
    use HasFactory;
    protected $table = 'user_answers';

    protected $fillable = ['user_id', 'question_id', 'choice_id', 'submitted_at', 'attempt_number'];

    public function user()
    {
        return $this->belongsTo(UsersDB::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(question::class, 'question_id');
    }

    public function choice()
    {
        return $this->belongsTo(choices::class, 'choice_id');
    }
    
}