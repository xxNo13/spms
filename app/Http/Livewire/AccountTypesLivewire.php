<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AccountType;

class AccountTypesLivewire extends Component
{
    public function render()
    {
        return view('livewire.account-types-livewire', [
            'account_types' => AccountType::all()
        ]);
    }
}
