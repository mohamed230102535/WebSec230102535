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
    protected $fillable = ['quiz_id', 'question_text'];

    public function quiz() {
        return $this->belongsTo(quiz::class);
    }

    public function answers() {
        return $this->hasMany(UserAnswer::class);
    }
    public function choices()
    {
        return $this->hasMany(choices::class, 'question_id');
    }
}
