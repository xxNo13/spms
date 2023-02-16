<?php

namespace App\Http\Livewire;

use App\Models\Office;
use Livewire\Component;

class OfficesLivewire extends Component
{
    public function render()
    {
        return view('livewire.offices-livewire',[
            'offices' => Office::all()
        ]);
    }
}
