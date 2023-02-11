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
    public $duration;
    public $targs;
    
    public function render()
    {
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->targs = Auth::user()->targets()->orderBy('id', 'ASC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->where('type', 'ipcr');
            })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                    return $query->where('type', 'ipcr');
                });
            })->where('duration_id', $this->duration->id)
            ->get();
        foreach($this->targs as $targ){
            $this->targets[$this->number] = $targ->target;
            if ($targ->rating) {
                $rating = Rating::where('target_id', $targ->id)
                    ->where('duration_id', $this->duration->id)
                    ->first();
                $this->ratings[$this->number] = $rating->average;
            } else {
                $this->ratings[$this->number] = 0;
            }

            $this->number++;
        }
        
        return view('livewire.ratingchart');
    }
}
