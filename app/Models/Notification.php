<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'is_read'    => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}