<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    protected $guarded = [];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function GetReceiver()
    {
        if ($this->sender_id === auth()->id()) {
            return User::firstWhere('id', $this->receiver_id);
        } else {
            return User::firstWhere('id', $this->sender_id);
        }
    }

    // Fixed the scope name and logic
  // In Conversation model
public function scopeWhereNotDeleted($query)
{
    $userId = auth()->id();
    
    return $query->where(function ($query) use ($userId) {
        // Check conversations where user is sender and hasn't deleted it
        $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->whereDoesntHave('messages', function ($messageQuery) use ($userId) {
                  // Check if sender has deleted all messages
                  $messageQuery->where('sender_id', $userId)
                               ->whereNotNull('sender_deleted_at');
              });
        })->orWhere(function ($q) use ($userId) {
            // Check conversations where user is receiver and hasn't deleted it
            $q->where('receiver_id', $userId)
              ->whereDoesntHave('messages', function ($messageQuery) use ($userId) {
                  // Check if receiver has deleted all messages
                  $messageQuery->where('receiver_id', $userId)
                               ->whereNotNull('receiver_deleted_at');
              });
        });
    });
}

    public function unreadMessageCount(): int
    {
        return Message::where('conversation_id', $this->id)
                     ->where('receiver_id', auth()->id())
                     ->whereNull('read_at')
                     ->count();
    }

    public function isLastMessageReadByUser(): bool
    {
        $user = Auth()->user();
        $lastMessage = $this->messages()->latest()->first();
        
        if ($lastMessage) {
            return $lastMessage->read_at !== null && $lastMessage->sender_id == $user->id;
        }
        
        return false;
    }
}