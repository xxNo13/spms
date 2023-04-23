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

    public function exports($file_path, $default_name) {
        return response()->download(storage_path('app/'.$file_path), $default_name);
    }
}
