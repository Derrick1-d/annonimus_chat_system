<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'receiver_id', 'message', 'is_anonymous'
    ];

    /**
     * Get the sender of the chat.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the chat.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    
}
