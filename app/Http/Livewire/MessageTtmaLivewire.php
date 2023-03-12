<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MessageTtmaLivewire extends Component
{
    public $ttma;
    
    public function render()
    {
        return view('livewire.message-ttma-livewire');
    }
}
