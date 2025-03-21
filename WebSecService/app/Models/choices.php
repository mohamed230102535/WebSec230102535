<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class choices extends Model {
    protected $table = 'choices';
    
    protected $fillable = ['question_id', 'choice_text', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(question::class, 'question_id');
    }
}