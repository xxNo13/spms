<?php

namespace App\Http\Livewire;

use App\Models\Rating;
use App\Models\Target;
use Livewire\Component;
use App\Models\Duration;
use Illuminate\Support\Facades\Auth;

class Ratingchart extends Component
{
    public $number = 0;
    public $targets = [];
    public $ratings = [];
    public $durationS;
    public $durationF;
    public $targs;
    
    public function render()
    {
        $this->durationS = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->durationF = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->targs = Auth::user()->targets()->orderBy('id', 'ASC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->where('type', 'ipcr');
            })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                    return $query->where('type', 'ipcr');
                });
            })->where('duration_id', $this->durationS->id)
            ->orwhere('duration_id', $this->durationF->id)
            ->get();
        foreach($this->targs as $targ){
            $this->targets[$this->number] = $targ->target;
            if ($targ->ratings()->where('user_id', auth()->user()->id)->first()) {
                $rating = $targ->ratings()->where('user_id', auth()->user()->id)->first();
                $this->ratings[$this->number] = $rating->average;
            } else {
                $this->ratings[$this->number] = 0;
            }

            $this->number++;
        }
        
        return view('livewire.ratingchart');
    }
}
