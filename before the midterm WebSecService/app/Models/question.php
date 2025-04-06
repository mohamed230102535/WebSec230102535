<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\quiz;
use App\Models\UserAnswer;
use App\Models\choices;

class question extends Model {
    use HasFactory;


    protected $table = 'question';

    protected $fillable = ['question_text', 'quiz_id'];

    public function quiz()
    {
        return $this->belongsTo(quiz::class, 'quiz_id');
    }

    public function choices()
    {
        return $this->hasMany(choices::class, 'question_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'question_id');
    }
}
