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
        if (!$durationStaff = Duration::where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->first()) {
            if (date('m') < '07') {
                $start_date = date('Y') . '-01-01';
                $end_date = date('Y') . '-06-30';
                $duration_name = 'CY ' . date('Y') . ' - First Semester';

                Duration::create([
                    'duration_name' => $duration_name,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'type' => 'staff',
                ]);
            } else {
                $start_date = date('Y') . '-07-01';
                $end_date = date('Y') . '-12-31';
                $duration_name = 'CY ' . date('Y') . ' - Second Semester';

                Duration::create([
                    'duration_name' => $duration_name,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'type' => 'staff',
                ]);
            }
        }
        
        if (!$durationFaculty = Duration::where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->first()) {
            $start_date = date('Y') . '-01-01';
            $end_date = date('Y') . '-12-31';
            $duration_name = 'CY ' . date('Y');

            Duration::create([
                'duration_name' => $duration_name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'type' => 'faculty',
            ]);
        }
        
        if (!$durationOffice = Duration::where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->first()) {
            $start_date = date('Y') . '-01-01';
            $end_date = date('Y') . '-12-31';
            $duration_name = 'CY ' . date('Y');

            Duration::create([
                'duration_name' => $duration_name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'type' => 'office',
            ]);
        }

        $this->durationS = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->first();
        $this->durationF = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->first();
        $this->durationO = Duration::orderBy('id', 'DESC')->where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->first();
        
        $this->approvalIPCRS = Approval::orderBy('id', 'DESC')
                ->where('name', 'approval')
                ->where('user_id', Auth::user()->id)
                ->where('user_type', 'staff')
                ->where('type', 'ipcr')
                ->where('duration_id', $this->durationS->id)
                ->first();
        
        $this->approvalStandardS = Approval::orderBy('id', 'DESC')
                ->where('name', 'approval')
                ->where('user_id', Auth::user()->id)
                ->where('user_type', 'staff')
                ->where('type', 'standard')
                ->where('duration_id', $this->durationS->id)
                ->first();

        $this->approvalIPCRF = Approval::orderBy('id', 'DESC')
                ->where('name', 'approval')
                ->where('user_id', Auth::user()->id)
                ->where('user_type', 'faculty')
                ->where('type', 'ipcr')
                ->where('duration_id', $this->durationF->id)
                ->first();
        
        $this->approvalStandardF = Approval::orderBy('id', 'DESC')
                ->where('name', 'approval')
                ->where('user_id', Auth::user()->id)
                ->where('user_type', 'faculty')
                ->where('type', 'standard')
                ->where('duration_id', $this->durationF->id)
                ->first();

        $this->targetsF = Auth::user()->targets()->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                    return $query->where('user_type', 'faculty')->where('type', 'ipcr');
                })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                    return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('user_type', 'faculty')->where('type', 'ipcr');
                    });
                })->where('duration_id', $this->durationF->id)->get();
                    
        $this->targetsS = Auth::user()->targets()->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                    return $query->where('user_type', 'staff')->where('type', 'ipcr');
                })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                    return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('user_type', 'staff')->where('type', 'ipcr');
                    });
                })->where('duration_id', $this->durationS->id)->get();
                        
        $this->ratings = Auth::user()->ratings()->whereHas('target', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                return $query->where('user_type', 'staff')->where('type', 'ipcr');
                            })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                                return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                    return $query->where('user_type', 'staff')->where('type', 'ipcr');
                                });
                            });
                        })->where('duration_id', $this->durationS->id)
                        ->orWhere('duration_id', $this->durationF->id)
                        ->get();

        $this->assignments = auth()->user()->ttmas()
                        ->where('duration_id', $this->durationO->id)
                        ->get();

        $this->finished = auth()->user()->ttmas()
                        ->where('duration_id', $this->durationO->id)
                        ->where('remarks', 'Done')
                        ->get();

        $this->recentTargets = Auth::user()->targets()->orderBy('id', 'DESC')->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->where('type', 'ipcr');
                        })->orwhereHas('suboutput', function (\Illuminate\Database\Eloquent\Builder $query) {
                            return $query->whereHas('output', function (\Illuminate\Database\Eloquent\Builder $query) {
                                return $query->where('type', 'ipcr');
                            });
                        })->where('duration_id', $this->durationS->id)
                        ->orWhere('duration_id', $this->durationF->id)
                        ->take(7)
                        ->get();
                        
        $this->recentAssignments = auth()->user()->ttmas()->orderBy('id', 'desc')
                        ->where('duration_id', $this->durationO->id)
                        ->take(7)
                        ->get();
        
        return view('livewire.dashboard');
    }
}
