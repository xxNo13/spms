<?php

namespace App\Http\Livewire;

use App\Models\Ttma;
use App\Models\Rating;
use App\Models\Target;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\Standard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        
        if ($this->duration) {
            $this->approvalIPCRS = Approval::orderBy('id', 'DESC')
                    ->where('name', 'approval')
                    ->where('user_id', Auth::user()->id)
                    ->where('user_type', 'staff')
                    ->where('type', 'ipcr')
                    ->where('duration_id', $this->duration->id)
                    ->first();
            
            $this->approvalStandardS = Approval::orderBy('id', 'DESC')
                    ->where('name', 'approval')
                    ->where('user_id', Auth::user()->id)
                    ->where('user_type', 'staff')
                    ->where('type', 'standard')
                    ->where('duration_id', $this->duration->id)
                    ->first();

            $this->approvalIPCRF = Approval::orderBy('id', 'DESC')
                    ->where('name', 'approval')
                    ->where('user_id', Auth::user()->id)
                    ->where('user_type', 'faculty')
                    ->where('type', 'ipcr')
                    ->where('duration_id', $this->duration->id)
                    ->first();
            
            $this->approvalStandardF = Approval::orderBy('id', 'DESC')
                    ->where('name', 'approval')
                    ->where('user_id', Auth::user()->id)
                    ->where('user_type', 'faculty')
                    ->where('type', 'standard')
                    ->where('duration_id', $this->duration->id)
                    ->first();

            $this->targetsF = Auth::user()->targets()->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('user_type', 'faculty')->where('type', 'ipcr');
                    })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('user_type', 'faculty')->where('type', 'ipcr');
                        });
                    })->where('duration_id', $this->duration->id)->get();
                        
            $this->targetsS = Auth::user()->targets()->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('user_type', 'staff')->where('type', 'ipcr');
                    })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('user_type', 'staff')->where('type', 'ipcr');
                        });
                    })->where('duration_id', $this->duration->id)->get();
                            
            $this->ratings = Auth::user()->ratings()->whereHas('target', function (\Illuminate\Database\Eloquent\Builder $query) {
                                return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                    return $query->where('user_type', 'staff')->where('type', 'ipcr');
                                })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                                    return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                        return $query->where('user_type', 'staff')->where('type', 'ipcr');
                                    });
                                });
                            })->where('duration_id', $this->duration->id)
                            ->get();

            $this->assignments = auth()->user()->ttmas()
                            ->where('duration_id', $this->duration->id)
                            ->get();

            $this->finished = auth()->user()->ttmas()
                            ->where('duration_id', $this->duration->id)
                            ->where('remarks', 'Done')
                            ->get();

            $this->recentTargets = Auth::user()->targets()->orderBy('id', 'DESC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                return $query->where('type', 'ipcr');
                            })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                                return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                    return $query->where('type', 'ipcr');
                                });
                            })->where('duration_id', $this->duration->id)
                            ->take(7)
                            ->get();
                            
            $this->recentAssignments = auth()->user()->ttmas()->orderBy('id', 'desc')
                            ->where('duration_id', $this->duration->id)
                            ->take(7)
                            ->get();
        }
        
        return view('livewire.dashboard');
    }
}
