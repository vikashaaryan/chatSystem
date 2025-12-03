<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts/app')]

class Chat extends Component
{
    public $query;
    public $selectedConversation;

    public function mount(){
        $this->selectedConversation = Conversation::findOrFail($this->query);
    }
    public function render()
    {
        return view('livewire.chat.chat');
    }
}
