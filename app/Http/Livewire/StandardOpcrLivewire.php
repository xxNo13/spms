<?php

namespace App\Http\Livewire;

use App\Models\Funct;
use Livewire\Component;
use App\Models\Duration;
use App\Models\Percentage;
use App\Models\SubPercentage;

class StandardOpcrLivewire extends Component
{
    public function mount(){ 
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->where('duration_id', $this->duration->id)->first();
            $this->sub_percentages = SubPercentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->where('duration_id', $this->duration->id)->get();
        }
    }

    public function render()
    {
        $functs = Funct::paginate(1);
        return view('livewire.standard-opcr-livewire', [
            'functs' => $functs
        ]);
    }
}
