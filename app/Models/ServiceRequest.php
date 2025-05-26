<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ServiceRequest extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'resolved_at'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'resolved_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the service request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include pending service requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include in progress service requests.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
    
    /**
     * Scope a query to only include completed service requests.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
