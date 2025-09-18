<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feedback extends Model
{
    protected $primaryKey = 'feedback_id';
    protected $fillable = ['user_id', 'message', 'created_at', 'is_approved'];
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
