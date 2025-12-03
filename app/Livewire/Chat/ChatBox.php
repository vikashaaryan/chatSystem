<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        $this->validate([
            'body' => 'required|string|max:1700',
        ]);
        
        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'receiver_id' => $this->selectedConversation->GetReceiver()->id,
            'sender_id' => auth()->id(),
            'body' => $this->body,
        ]);
        
        $this->reset('body');
        
        // Reload messages to include the new one
        $this->loadMessages();
        
        // Dispatch event for Alpine.js
        $this->dispatch('scroll-bottom');
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}