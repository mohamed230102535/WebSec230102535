<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\question;
use App\Models\User;

class quiz extends Model {
    use HasFactory;
    protected $table = 'quiz';

    protected $fillable = ['title', 'description', 'created_by'];

    public function questions() {
        return $this->hasMany(question::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
