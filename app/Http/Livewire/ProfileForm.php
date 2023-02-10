<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Actions\Fortify\UpdateUserProfileInformation;

class ProfileForm extends Component
{
    public $state = [];
    public $office = [];
    public $offices = [];

    public function mount()
    {
        $this->state = auth()->user()->withoutRelations()->toArray();

        foreach (auth()->user()->offices as $office) {
            $this->office[$office->id] = $office->pivot->isHead;
        }

        foreach (auth()->user()->offices as $office) {
            $this->offices[$office->id] = $office;
        }
    }

    public function render()
    {
        return view('livewire.profile-form');
    }

    public function updateProfileInformation(UpdateUserProfileInformation $updater)
    {

        $this->state['office'] = $this->office;

        $this->resetErrorBag();

        $updater->update(auth()->user(), $this->state);

        session()->flash('status', 'Profile successfully updated');
    }
}
