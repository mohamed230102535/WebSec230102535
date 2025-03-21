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
    protected $fillable = ['question_id', 'user_id', 'choice_id', 'attempt_number'];
  
  
    public function user() {
        return $this->belongsTo(User::class);
    }
 

    public function question()
    {
        return $this->belongsTo(question::class);
    }
    public function choice()
    {
        return $this->belongsTo(choices::class, 'choice_id');
    }
    
}