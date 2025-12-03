<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts/app')]

class Chat extends Component
{
    public $query;
    public $selectedConversation;

    public function mount(){
        $this->selectedConversation = Conversation::findOrFail($this->query);


        Message::where('conversation_id',$this->selectedConversation->id)->where('receiver_id',auth()->id())->where('read_at')->update(['read_at'=>now()]);
    }
    public function render()
    {
        return view('livewire.chat.chat');
    }
}
