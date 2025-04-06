<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\question;
use App\Models\User;

class quiz extends Model {
    use HasFactory;

    use HasFactory;

    protected $table = 'quiz';

    protected $fillable = ['title', 'description', 'created_by'];

    public function questions()
    {
        return $this->hasMany(question::class, 'quiz_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(UsersDB::class, 'created_by');
    }

}
