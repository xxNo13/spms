<?php

namespace App\Http\Livewire;

use App\Models\Rating;
use App\Models\Target;
use Livewire\Component;
use App\Models\Approval;
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
        $this->approvalS = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $this->durationS->id)->where('user_type', 'staff')->first();
        $this->approvalF = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $this->durationF->id)->where('user_type', 'faculty')->first();

        $staff = false;
        $faculty = false;

        foreach (auth()->user()->account_types as $account_type) {
            if (str_contains(strtolower($account_type), 'staff')) {
                $staff = true;
            }
            if (str_contains(strtolower($account_type), 'faculty')) {
                $faculty = true;
            }
        }

        $this->targs = [];

        if ($staff && $faculty) {
            if ($this->approvalS && $this->approvalF) {
                $this->targs = Auth::user()->targets()->orderBy('id', 'ASC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('type', 'ipcr');
                    })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('type', 'ipcr');
                        });
                    })->where('duration_id', $this->durationS->id)
                    ->orwhere('duration_id', $this->durationF->id)
                    ->get();
            } elseif ($this->approvalS) {
                $this->targs = Auth::user()->targets()->orderBy('id', 'ASC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('type', 'ipcr')->where('user_type', 'staff');
                    })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('type', 'ipcr')->where('user_type', 'staff');
                        });
                    })->where('duration_id', $this->durationS->id)
                    ->get();
            } elseif ($this->approvalF) {
                $this->targs = Auth::user()->targets()->orderBy('id', 'ASC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('type', 'ipcr')->where('user_type', 'faculty');
                    })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('type', 'ipcr')->where('user_type', 'faculty');
                        });
                    })->where('duration_id', $this->durationF->id)
                    ->get();
            }
        } elseif ($staff) {
            if ($this->approvalS) {
                $this->targs = Auth::user()->targets()->orderBy('id', 'ASC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('type', 'ipcr')->where('user_type', 'staff');
                    })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('type', 'ipcr')->where('user_type', 'staff');
                        });
                    })->where('duration_id', $this->durationS->id)
                    ->get();
            }
        } elseif ($faculty) {
            if ($this->approvalF) {
                $this->targs = Auth::user()->targets()->orderBy('id', 'ASC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('type', 'ipcr')->where('user_type', 'faculty');
                    })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('type', 'ipcr')->where('user_type', 'faculty');
                        });
                    })->where('duration_id', $this->durationF->id)
                    ->get();
            }
        }

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
