<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class choices extends Model {
    protected $table = 'choices';

    protected $fillable = ['choice_text', 'is_correct', 'question_id'];

    public function question()
    {
        return $this->belongsTo(question::class, 'question_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'choice_id');
    }
}