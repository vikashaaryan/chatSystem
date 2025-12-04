<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;
    public $paginate_var = 9; 
    public $loading = false;
    public $lastMessageId = null;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->loadMessages();
        // Store the ID of the latest message
        $this->lastMessageId = $this->loadedMessages->last()?->id;
    }

    // ADD THIS METHOD - It's being called from your Blade view
    public function loadmore()
    {
        if ($this->loading) {
            return;
        }

        $this->loading = true;
        
        $oldCount = $this->loadedMessages->count();
        $this->paginate_var += 9;
        $this->loadMessages();
        
        if ($this->loadedMessages->count() > $oldCount) {
            $this->dispatch('scroll-to-position', [
                'position' => $this->loadedMessages->count() - $oldCount
            ]);
        }
        
        $this->loading = false;
    }

    // Add polling
    public function checkForNewMessages()
    {
        if (!$this->selectedConversation) return;

        $latestMessage = Message::where('conversation_id', $this->selectedConversation->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestMessage && $latestMessage->id != $this->lastMessageId) {
            $this->loadMessages();
            $this->lastMessageId = $latestMessage->id;
            
            if ($latestMessage->sender_id != auth()->id()) {
                $this->dispatch('scroll-to-bottom');
            }
        }
        Message::where('conversation_id',$this->selectedConversation->id)->where('receiver_id',auth()->id())->where('read_at')->update(['read_at'=>now()]);
    }

    public function loadMessages()
    {
        $count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $skip = max(0, $count - $this->paginate_var);

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->orderBy('created_at', 'asc') 
            ->skip($skip)
            ->take($this->paginate_var)
            ->get();

        // Update last message ID
        $this->lastMessageId = $this->loadedMessages->last()?->id;

        return $this->loadedMessages;
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

        $this->dispatch('clear-input');
        $this->reset('body');
        $this->loadMessages();
        
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        $this->dispatch('refresh')->to('chat.chat-list');
        $this->dispatch('scroll-to-bottom');
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}