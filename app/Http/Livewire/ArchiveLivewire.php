<?php

namespace App\Http\Livewire;

use App\Models\Funct;
use Livewire\Component;
use App\Models\Duration;
use App\Models\Percentage;
use App\Models\AccountType;
use App\Models\SubPercentage;
use Livewire\WithPagination;

class ArchiveLivewire extends Component
{
    use WithPagination;
    
    public $durations;

    public $viewed = false;

    public $duration;
    public $type;
    public $user_type;
    public $category;

    public $search;

    public $print;

    protected  $queryString = ['search'];

    public function updated($property)
    {
        if ($property == 'search') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $durations = Duration::query();

        if ($this->search) {
            $search = $this->search;
            
            $results = preg_split('/\s+/', strtolower($search));

            foreach ($results as $result) {
                $durations->where(function ($query) use ($result) {
                    return $query->where('type', 'LIKE', '%'.$result.'%')->orwhere('duration_name', 'LIKE', '%'.$result.'%');
                });
            }

            if (str_contains($search, 'opcr')) {
                $durations->where('type', 'office');
            }
            if (str_contains($search, 'ipcr')) {
                $durations->where(function ($query) {
                    return $query->where('type', 'staff')->orwhere('type', 'faculty');
                });
            }
        }

        $durations->distinct();
        $this->durations = $durations->where('end_date', '<=', date('Y-m-d'))->get();


        if (isset($this->category)) {
            $functs = Funct::all();
            return view('components.archives-standard',[
                'functs' => $functs,
            ]);
        }
        if ($this->viewed) {
            $functs = Funct::all();
            return view('components.archives',[
                'functs' => $functs
            ]);
        }
        return view('livewire.archive-livewire');
    }

    public function viewed($duration_id, $type, $user_type, $category = null){
        $this->duration = Duration::find($duration_id);
        $this->type = $type;
        $this->user_type = $user_type;
        $this->category = $category;

        if ($type == 'opcr' && $user_type == 'office') {
            $this->percentage = Percentage::where('type', $type)->where('user_type', $user_type)->where('user_id', null)->where('duration_id', $duration_id)->first();
            $this->sub_percentages = SubPercentage::where('type', $type)->where('user_type', $user_type)->where('user_id', null)->where('duration_id', $duration_id)->get();
        } elseif ($type == 'ipcr' && $user_type == 'faculty') {
            $this->percentage = Percentage::where('type', 'ipcr')->where('user_type', 'faculty')->where('user_id', null)->where('duration_id', $this->duration->id)->first();
        } elseif ($type == 'ipcr' && $user_type == 'staff') {
            $this->percentage = Percentage::where('type', 'ipcr')->where('user_type', 'staff')->where('user_id', auth()->user()->id)->where('duration_id', $this->duration->id)->first();
        }
        
        $this->viewed = true;
    }

    public function print() {
        $this->print = $this->user_type;
    }

    public function closeModal(){
        $this->dispatchBrowserEvent('close-modal'); 
    }
}
