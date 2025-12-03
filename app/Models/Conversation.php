<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded = [];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function GetReceiver()
    {
        if ($this->sender_id === auth()->id()) {
            return User::firstWhere('id', operator: $this->receiver_id);
        } else {
            return User::firstWhere('id', operator: $this->sender_id);

        }
    }

    public function unreadMessageCount(): int
    {
        return $unreadMessage = Message::where('conversation_id', '=', $this->id)->where('receiver_id', auth()->user()->id)->whereNull('read_at')->count();
    }

    public function isLastMessageReadByUser():bool
    {
        $user = Auth()->User();
        $lastMessage = $this->messages()->latest()->first();
        if ($lastMessage) {
            return $lastMessage->read_at !== null && $lastMessage->sender_id == $user->id;
        }
    }
}
