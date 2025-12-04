<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MessageSent extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $user;
    public $message;
    public $conversation;
    public $receiverId;

    public function __construct($user, $message, $conversation, $receiverId)
    {
        $this->user = $user;
        $this->message = $message;
        $this->conversation = $conversation;
        $this->receiverId = $receiverId;
        
        // Set a delay to ensure the message is saved
        $this->delay(now()->addSeconds(1));
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        \Log::info('Broadcasting notification to user: ' . $notifiable->id);
        \Log::info('Broadcast channel: users.' . $notifiable->id);
        
        return new BroadcastMessage([
            'user_id' => $this->user->id,
            'conversation_id' => $this->conversation->id,
            'message_id' => $this->message->id,
            'receiver_id' => $this->receiverId,
            'sender_name' => $this->user->name,
            'message_body' => $this->message->body,
            'timestamp' => now()->toDateTimeString(),
            'type' => 'message.sent'
        ]);
    }

    public function broadcastOn()
    {
        // This is CRITICAL - it must match the channel you're listening to
        return ['users.' . $this->receiverId];
    }

    public function broadcastAs()
    {
        // Use a dot notation for the event name
        return 'MessageSent';
    }
}