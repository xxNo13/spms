<?php

namespace App\Http\Livewire;

use App\Models\Rating;
use Livewire\Component;
use App\Models\Duration;
use Illuminate\Support\Facades\Auth;

class Targetchart extends Component
{
    public $month;
    public $dateToday;
    public $duration;
    public $days;
    public $targets;

    public function render()
    {
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->dateToday = date('d');
        $this->month = date('Y-m-d');
        $this->days = collect(range($this->dateToday - 6, $this->dateToday))->map(function ($number) {
            if ($number < 1) {
                $maxDate = date("t", strtotime(date('M', strtotime($this->month. '- 1 months'))));

                return date('M', strtotime($this->month. '- 1 months')) . " " . $maxDate + $number;
            } else{
                return date('M', strtotime($this->month)) . " " . $number;
            }
        });
        $ratings = Auth::user()->ratings()->whereHas('target', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('type', 'ipcr');
                        })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                return $query->where('type', 'ipcr');
                            });
                        });
                    })->where('duration_id', $this->duration->id)
                    ->get();
        $this->targets = [0,0,0,0,0,0,0];
        foreach($ratings as $rating) {
            if(date('M-d-Y', strtotime($rating->created_at)) == date('M-d-Y')){
                ++$this->targets[6];
            }elseif(date('M-d-Y', strtotime($rating->created_at)) == date('M-d-Y', strtotime(date('M-d-Y'). '- 1 days'))) {
                ++$this->targets[5];
            }elseif(date('M-d-Y', strtotime($rating->created_at)) == date('M-d-Y', strtotime(date('M-d-Y'). '- 2 days'))) {
                ++$this->targets[4];
            }elseif(date('M-d-Y', strtotime($rating->created_at)) == date('M-d-Y', strtotime(date('M-d-Y'). '- 3 days'))) {
                ++$this->targets[3];
            }elseif(date('M-d-Y', strtotime($rating->created_at)) == date('M-d-Y', strtotime(date('M-d-Y'). '- 4 days'))) {
                ++$this->targets[2];
            }elseif(date('M-d-Y', strtotime($rating->created_at)) == date('M-d-Y', strtotime(date('M-d-Y'). '- 5 days'))) {
                ++$this->targets[1];
            }elseif(date('M-d-Y', strtotime($rating->created_at)) == date('M-d-Y', strtotime(date('M-d-Y'). '- 6 days'))) {
                ++$this->targets[0];
            }
        }
        
        return view('livewire.targetchart');
    }
}
