<?php

namespace App\Http\Livewire;

use App\Models\Office;
use Livewire\Component;
use App\Models\AccountType;
use App\Actions\Fortify\UpdateUserProfileInformation;

class ProfileForm extends Component
{
    public $state = [];
    public $isHead = [];
    public $offices;
    public $office;
    public $account_types;
    public $account_type;
    public $institute;
    public $isProgramChair = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->offices = Office::orderBy('office_name', 'ASC')->get();

        $this->account_types = AccountType::orderBy('account_type', 'ASC')->get();

        $this->state = auth()->user()->withoutRelations()->toArray();

        $this->office = auth()->user()->offices->pluck('id')->toArray();

        $this->account_type = auth()->user()->account_types->pluck('id')->toArray();

        $this->institute = auth()->user()->institutes->pluck('id')->first();

        foreach (auth()->user()->offices as $office) {
            $this->isHead[$office->id] = $office->pivot->isHead;
        }

        foreach (auth()->user()->institutes as $institute) {
            $this->isProgramChair[$institute->id] = $institute->pivot->isProgramChair;
        }
    }

    public function render()
    {
        $institutes = [];

        if (isset($this->office[0])) {
            foreach ($this->office as $office_id) {
                $office = Office::find($office_id);

                foreach ($office->institutes as $institute) {
                    array_push($institutes, $institute);
                }
            }

            array_unique($institutes);
        }

        return view('livewire.profile-form', [
            'institutes' => $institutes
        ]);
    }

    public function updateProfileInformation(UpdateUserProfileInformation $updater)
    {
        $this->state['office'] = $this->office;
        $this->state['account_type'] = $this->account_type;
        $this->state['isHead'] = $this->isHead;
        $this->state['institute'] = $this->institute;
        $this->state['isProgramChair'] = $this->isProgramChair;

        $this->resetErrorBag();

        $updater->update(auth()->user(), $this->state);
        $this->resetInput();
            
        $this->dispatchBrowserEvent('toastify', [
            'message' => "Profile Information Saved",
            'color' => "#28ab55",
        ]);

        return redirect(request()->header('Referer'));
    }

    public function resetInput() {
        $this->state = [];
        $this->isHead = [];
        $this->offices = "";
        $this->office = "";
        $this->account_types = "";
        $this->account_type = "";
        $this->institute = "";
        $this->isProgramChair = '';
    }
}
