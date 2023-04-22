<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Pmt;
use App\Models\User;
use App\Models\Funct;
use App\Models\Rating;
use App\Models\Target;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\Percentage;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApprovalNotification;

class ForApprovalLivewire extends Component
{
    use WithPagination;

    public $view = false;
    public $category = '';
    public $user_id = '';
    public $url = '';
    public $user_type = '';
    public $approval;
    public $search;
    public $duration;
    public $comment = [];
    public $approving;
    public $percentage;
    public $selected;
    public $filterA;
    public $pmts;

    public $rating_id;
    public $selectedTarget;
    public $output_finished;
    public $efficiency;
    public $quality;
    public $timeliness;
    public $accomplishment;

    public $targetOutput;

    public $selectedTargetId;

    protected  $queryString = ['search'];

    public function viewed($approval, $url){
        $this->user_id = $approval['user_id'];
        $this->category = $approval['type'];
        $this->url = $url;
        $this->view = true;
        $this->user_type = $approval['user_type'];
        $this->approval = Approval::find($approval['id']);

        if ($approval['user_type'] == 'staff') {
            $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->first();
        } elseif ($approval['user_type'] == 'faculty') {
            $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->first();
        }if ($approval['user_type'] == 'office') {
            $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->first();
        }

        $this->prevApproval = Approval::orderBy('created_at', 'DESC')
                ->where('user_id', $approval['user_id'])
                ->where('type', $approval['type'])
                ->where('user_type', $approval['user_type'])
                ->where('duration_id', $this->duration->id)
                ->where('name', $this->approval->name)
                ->where('id', '<', $this->approval->id)
                ->where(function ($query) {
                    $query->whereHas('reviewers', function (\Illuminate\Database\Eloquent\Builder $query) {
                        return $query->where('review_message', '!=', null);
                    })->orWhere('approve_message', '!=', null);
                })->first();
        if ($this->prevApproval) {
            $this->prevApprover = User::find($this->prevApproval->approve_id);
        }
        if ($approval['type'] == 'standard') {
            if ($approval['user_type'] == 'office') {
                $this->percentage = Percentage::where('user_id', $approval['user_id'])
                    ->where('type', 'opcr')
                    ->where('user_type', $approval['user_type'])
                    ->where('duration_id', $this->duration->id)
                    ->first();
            } else {
                if ($approval['user_type'] == 'faculty') {
                    $this->percentage = Percentage::where('type', 'ipcr')
                        ->where('user_type', $approval['user_type'])
                        ->where('duration_id', $this->duration->id)
                        ->first();
                } else {
                    $this->percentage = Percentage::where('user_id', $approval['user_id'])
                        ->where('type', 'ipcr')
                        ->where('user_type', $approval['user_type'])
                        ->where('duration_id', $this->duration->id)
                        ->first();
                }
            }
        } else {
            if ($approval['user_type'] != 'staff') {
                $this->percentage = Percentage::where('type', $approval['type'])
                    ->where('user_type', $approval['user_type'])
                    ->where('duration_id', $this->duration->id)
                    ->first();
            } else {
                $this->percentage = Percentage::where('user_id', $approval['user_id'])
                    ->where('type', $approval['type'])
                    ->where('user_type', $approval['user_type'])
                    ->where('duration_id', $this->duration->id)
                    ->first();
            }
        }
    }

    public function render()
    {
        $this->pmts = Pmt::all()->pluck('user_id')->toArray();
        $this->durationS = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->durationF = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->durationO = Duration::orderBy('id', 'DESC')->where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->first();

        
        if ($this->view && $this->category == 'ipcr'){
            $functs = Funct::all();
            $user = User::find($this->user_id);
            return view('components.individual-ipcr',[
                'functs' => $functs,
                'user' => $user,
                'number' => 1,
            ]);
        } elseif ($this->view && $this->category == 'opcr'){
            $functs = Funct::all();
            $user = User::find($this->user_id);
            return view('components.individual-opcr',[
                'functs' => $functs,
                'user' => $user,
                'number' => 1,
            ]);
        } elseif ($this->view && $this->category == 'standard'){
            if ($this->user_type == 'office') {
                $functs = Funct::all();
                $user = User::find($this->user_id);
                return view('components.individual-standard',[
                    'functs' => $functs,
                    'user' => $user,
                    'type' => 'opcr',
                    'number' => 1,
                ]);
            } else {
                $functs = Funct::all();
                $user = User::find($this->user_id);
                return view('components.individual-standard',[
                    'functs' => $functs,
                    'user' => $user,
                    'type' => 'ipcr',
                    'number' => 1,
                ]);
            }
        } else {
            $approvals = Approval::query();

            if ($this->search) {
                $search = $this->search;
                $approvals->where(function ($query) use ($search) {
                    return $query->whereHas('user', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                        return $query->where('name', 'LIKE','%'.$search.'%')
                            ->orWhere('email','LIKE','%'.$search.'%')
                            ->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                                return $query->where('office_abbr', 'LIKE','%'.$search.'%');
                            })->orWhereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                                return $query->where('account_type', 'LIKE','%'.$search.'%');
                            });
                    })->orWhere('type','LIKE','%'.$search.'%')
                    ->orWhere('user_type','LIKE','%'.$search.'%')
                    ->orWhere('name','LIKE','%'.$search.'%');
                })->where(function ($query) {
                    return $query->where('duration_id', $this->durationS->id)
                            ->where('duration_id', $this->durationF->id)
                            ->where('duration_id', $this->durationO->id)->get();
                });
            }
            
            if (isset($this->filterA) && $this->filterA == 'noremark') {
                $approvals->where(function ($query){
                    return $query->whereHas('reviewers', function(\Illuminate\Database\Eloquent\Builder $result) {
                        return $result->where('user_id', auth()->user()->id)->where('review_status', null);
                    });
                })->orwhere(function ($query){
                    $query->where('approve_id', auth()->user()->id)->where('approve_status', null);
                });
            } elseif (isset($this->filterA) && $this->filterA == 'remark') {
                $approvals->where(function ($query){
                    return $query->whereHas('reviewers', function(\Illuminate\Database\Eloquent\Builder $result) {
                        return $result->where('user_id', auth()->user()->id)->where('review_status', '!=', null);
                    });
                })->orwhere(function ($query){
                    $query->where('approve_id', auth()->user()->id)->where('approve_status', '!=', null);
                });
            }

            return view('livewire.for-approval-livewire', [
                'approvals' => $approvals->paginate(25),
            ]);

        }
    }

    public function rating($target_id = null, $rating_id = null){
        $user = User::find($this->user_id);

        $this->selected = 'rating';
        $this->rating_id = $rating_id;
        $this->target_id = $target_id;
        if ($target_id) {
            $this->selectedTarget = $user->targets()->where('id', $target_id)->first();
            $this->targetOutput = $this->selectedTarget->pivot->target_output;
        }
    }

    public function editRating($rating_id){
        $user = User::find($this->user_id);
        
        $this->selected = 'rating';
        $this->rating_id = $rating_id;

        $rating = $user->ratings()->where('id', $rating_id)->first();

        $this->selectedTarget = $user->targets()->where('id', $rating->target_id)->first();
        $this->targetOutput = $this->selectedTarget->pivot->target_output;
        
        $this->output_finished = $rating->output_finished;
        $this->accomplishment = $rating->accomplishment;
        $this->quality = $rating->quality;
        $this->timeliness = $rating->timeliness;
    }

    public function saveRating($category){

        
        $divisor = 0;
        
        $standard = $this->selectedTarget->standards()->first();

        if ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) {
            if ($standard->eff_5) {
                $eff_5 = strtok($standard->eff_5, '%');
            }
            if ($standard->eff_4) {
                $eff_4 = strtok($standard->eff_4, '%');
            }
            if ($standard->eff_3) {
                $eff_3 = strtok($standard->eff_3, '%');
            }
            if ($standard->eff_2) {
                $eff_2 = strtok($standard->eff_2, '%');
            }

            if (str_contains($standard->eff_5, '%')) {
                $output_percentage = $this->output_finished/$this->targetOutput * 100;
            } else {
                $output_percentage = $this->output_finished;
            }
            
            if (isset($eff_5) && $output_percentage >= (float)$eff_5) {
                $efficiency = 5;
            } elseif (isset($eff_4) && $output_percentage >= (float)$eff_4) {
                $efficiency = 4;
            } elseif (isset($eff_3) && $output_percentage >= (float)$eff_3) {
                $efficiency = 3;
            } elseif (isset($eff_2) && $output_percentage >= (float)$eff_2) {
                $efficiency = 2;
            } else {
                $efficiency = 1;
            }
        }

        if ($this->quality == '') {
            if ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1){
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'quality' => ['Quality cannot be null.'],
                    ]);
                    throw $error;
            } else {
                $this->quality = null;
            }
        }
        if ($this->timeliness == '') {
            if ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1){
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'timeliness' => ['Timeliness cannot be null.'],
                    ]);
                    throw $error;
            } else {
                $this->timeliness = null;
            }
        }

        if(!$efficiency){
            $divisor++;
        }
        if(!$this->quality){
            $divisor++;
        }
        if(!$this->timeliness){
            $divisor++;
        }
        $number = ((int)$efficiency + (int)$this->quality + (int)$this->timeliness) / (3 - $divisor);
        $average = number_format((float)$number, 2, '.', '');

        Rating::where('id', $this->rating_id)->update([
            'output_finished' => $this->output_finished,
            'accomplishment' => $this->accomplishment,
            'efficiency' => $efficiency,
            'quality' => $this->quality,
            'timeliness' => $this->timeliness,
            'average' => $average,
        ]);

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Updated Successfully",
            'color' => "#28ab55",
        ]);
        

        $this->output_finished = '';
        $this->efficiency = '';
        $this->quality = '';
        $this->timeliness = '';
        $this->accomplishment = '';
        $this->dispatchBrowserEvent('close-modal');
    }
        
    public function approved($id, $type, $bool = false){
        $approval = Approval::find($id);

        if ($approval->approve_id == Auth::user()->id && $type == 'Approved'){
            foreach ($this->comment as $id => $value) {
                $target = Target::find($id);

                $this->comment[$id] = $target->target . ": " . $value;
            }
            Approval::where('id', $id)->update([
                'approve_status' => 1,
                'approve_date' => Carbon::now(),
                'approve_message' => implode("\n", $this->comment)
            ]);
            $head = User::where('id', $approval->approve_id)->first();

        } elseif (auth()->user()->user_approvals()->where('approval_id', $approval->id)->first() && $type == 'Reviewed'){
            
            foreach ($this->comment as $id => $value) {
                $target = Target::find($id);

                $this->comment[$id] = $target->target . ": " . $value;
            }

            auth()->user()->user_approvals()->syncWithoutDetaching([$approval->id => [
                'review_status' => 1,
                'review_date' => Carbon::now(),
                'review_message' => implode("\n", $this->comment)
            ]]);

            $head = auth()->user();

            if ($approval->approve_id == Auth::user()->id) {
                Approval::where('id', $id)->update([
                    'approve_status' => 1,
                    'approve_date' => Carbon::now()
                ]);
            }
        }

        $user = User::where('id', $approval->user_id)->first();

        $user->notify(new ApprovalNotification($approval, $head, $type));

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Approved Successfully",
            'color' => "#435ebe",
        ]);
        $this->resetInput();
        $this->mount();
        $this->dispatchBrowserEvent('close-modal'); 

        if ($bool) {
            return redirect(request()->header('Referer'));
        }
    }

    public function comment($target_id) {
        $this->selectedTargetId = $target_id;
    }

    public function declined($id, $bool = false){
        
        $this->approving = Approval::find($id);
        $this->bool = $bool;

        if (auth()->user()->user_approvals()->where('approval_id', $this->approving->id)->first()){

            foreach ($this->comment as $id => $value) {
                $target = Target::find($id);

                $this->comment[$id] = $target->target . ": " . $value;
            }

            auth()->user()->user_approvals()->syncWithoutDetaching([$this->approving->id => [
                'review_status' => 2,
                'review_date' => Carbon::now(),
                'review_message' => implode("\n", $this->comment)
            ]]);

            foreach ($this->approving->reviewers()->where('review_status', null)->get() as $reviewer) {
                $reviewer->user_approvals()->syncWithoutDetaching([$this->approving->id => [
                    'review_status' => 3,
                    'review_date' => Carbon::now()
                ]]);
            }

            
            Approval::where('id', $this->approving->id)->update([
                'approve_status' => 3,
                'approve_date' => Carbon::now()
            ]);

            $head = auth()->user();
            
        } elseif ($this->approving->approve_id == Auth::user()->id){
            foreach ($this->comment as $id => $value) {
                $target = Target::find($id);

                $this->comment[$id] = $target->target . ": " . $value;
            }
            Approval::where('id', $this->approving->id)->update([
                'approve_status' => 2,
                'approve_date' => Carbon::now(),
                'approve_message' => implode("\n", $this->comment)
            ]);
            $head = User::where('id', $this->approving->approve_id)->first();
        }

        $user = User::where('id', $this->approving->user_id)->first();

        $user->notify(new ApprovalNotification($this->approving, $head, 'Declined'));

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Decline Successfully",
            'color' => "#f3616d",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
        $this->mount();

        if ($this->bool) {
            return redirect(request()->header('Referer'));
        }
    } 

    public function resetInput(){
        $this->view = false;
        $this->category = '';
        $this->user_id = '';
        $this->url = '';
        $this->approval = '';
        $this->user_type = '';
        $this->approving = '';
        $this->selected = '';
        $this->comment = [];

        $this->output_finished = '';
        $this->efficiency = '';
        $this->quality = '';
        $this->timeliness = '';
        $this->accomplishment = '';
    }

    public function closeModal(){
        $this->dispatchBrowserEvent('close-modal'); 
    }
}
