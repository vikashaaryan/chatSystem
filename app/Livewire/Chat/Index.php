<?php

namespace App\Livewire\Chat;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts/app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.chat.index');
    }
}
