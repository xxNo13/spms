<?php

namespace App\Http\Livewire;

use App\Models\Funct;
use Livewire\Component;
use App\Models\Duration;
use App\Models\Percentage;

class StandardFacultyLivewire extends Component
{
    public function mount(){ 
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'ipcr')->where('user_type', 'faculty')->where('user_id', null)->where('duration_id', $this->duration->id)->first();
        }
    }

    public function render()
    {
        $functs = Funct::paginate(1);
        return view('livewire.standard-faculty-livewire', [
            'functs' => $functs
        ]);
    }
}
