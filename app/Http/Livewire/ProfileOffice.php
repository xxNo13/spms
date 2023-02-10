<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProfileOffice extends Component
{
    public function render()
    {
        return view('livewire.profile-office', [
            'offices' => Auth::user()->offices,
        ]);
    }
}
