<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Funct;
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
    public $userType = '';
    public $approval;
    public $search;
    public $duration;
    public $comment;
    public $approving;
    public $percentage;

    protected  $queryString = ['search'];

    public function viewed($user_id, $category, $url, $userType){
        $this->user_id = $user_id;
        $this->category = $category;
        $this->url = $url;
        $this->view = true;
        $this->userType = $userType;
        $this->approval = Approval::orderBy('id', 'DESC')
                ->where('user_id', $user_id)
                ->where('type', $category)
                ->where('user_type', $userType)
                ->where('duration_id', $this->duration->id)
                ->first();
        if ($category == 'standard') {
            if ($userType == 'office') {
                $this->percentage = Percentage::where('user_id', $user_id)
                    ->where('type', 'opcr')
                    ->where('userType', $userType)
                    ->where('duration_id', $this->duration->id)
                    ->first();
            } else {
                $this->percentage = Percentage::where('user_id', $user_id)
                    ->where('type', 'ipcr')
                    ->where('userType', $userType)
                    ->where('duration_id', $this->duration->id)
                    ->first();
            }
        } else {
            $this->percentage = Percentage::where('user_id', $user_id)
                ->where('type', $category)
                ->where('userType', $userType)
                ->where('duration_id', $this->duration->id)
                ->first();
        }
    }

    public function render()
    {
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        
        if ($this->view && $this->category == 'ipcr'){
            $functs = Funct::all();
            $user = User::find($this->user_id);
            return view('components.individual-ipcr',[
                'functs' => $functs,
                'user' => $user,
                'url' => $this->url,
                'approval' => $this->approval,
                'duration' => $this->duration,
                'userType' => $this->userType,
                'percentage' => $this->percentage,
                'number' => 1
            ]);
        } elseif ($this->view && $this->category == 'opcr'){
            $functs = Funct::all();
            $user = User::find($this->user_id);
            return view('components.individual-opcr',[
                'functs' => $functs,
                'user' => $user,
                'url' => $this->url,
                'approval' => $this->approval,
                'duration' => $this->duration,
                'userType' => $this->userType,
                'percentage' => $this->percentage,
                'number' => 1
            ]);
        } elseif ($this->view && $this->category == 'standard'){
            if ($this->userType == 'office') {
                $functs = Funct::all();
                $user = User::find($this->user_id);
                return view('components.individual-standard',[
                    'functs' => $functs,
                    'user' => $user,
                    'url' => $this->url,
                    'type' => 'opcr',
                    'approval' => $this->approval,
                    'duration' => $this->duration,
                    'userType' => $this->userType,
                    'percentage' => $this->percentage,
                    'number' => 1
                ]);
            } else {
                $functs = Funct::all();
                $user = User::find($this->user_id);
                return view('components.individual-standard',[
                    'functs' => $functs,
                    'user' => $user,
                    'url' => $this->url,
                    'type' => 'ipcr',
                    'approval' => $this->approval,
                    'duration' => $this->duration,
                    'userType' => $this->userType,
                    'percentage' => $this->percentage,
                    'number' => 1
                ]);
            }
        } else {
            $search = $this->search;
            $approvals = Approval::query();

            if ($search) {
                if ($this->duration) {
                    $approvals->where(function ($query) use ($search) {
                        $query->whereHas('user', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
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
                    })
                    ->where('duration_id', $this->duration->id)->get();
                } else {
                    $approvals->whereHas('user', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                        return $query->where('name', 'LIKE','%'.$search.'%')
                            ->orWhere('email','LIKE','%'.$search.'%')
                            ->orwhereHas('office', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                                return $query->where('office_abbr', 'LIKE','%'.$search.'%');
                            });
                    })
                    ->orWhere('type','LIKE','%'.$search.'%')
                    ->orWhere('user_type','LIKE','%'.$search.'%')
                    ->orWhere('name','LIKE','%'.$search.'%')
                    ->get();
                }
            }

            if ($this->duration) {
                return view('livewire.for-approval-livewire', [
                    'approvals' => $approvals->orderBy('created_at','DESC')->where('duration_id', $this->duration->id)->paginate(25),
                ]);
            }
            return view('livewire.for-approval-livewire', [
                'approvals' => $approvals->orderBy('created_at','DESC')->paginate(25),
            ]);
        }
    }

    public function updated($property)
    {
        if ($property == 'search') {
            $this->resetPage();
        }
    }
        
    public function approved($id){
        $approval = Approval::find($id);

        if ($approval->review_id == Auth::user()->id){
            Approval::where('id', $id)->update([
                'review_status' => 1,
                'review_date' => Carbon::now(),
            ]);
            $head = User::where('id', $approval->review_id)->first();
            
            $status = 'Reviewed';
        } elseif ($approval->approve_id == Auth::user()->id){
            Approval::where('id', $id)->update([
                'approve_status' => 1,
                'approve_date' => Carbon::now(),
            ]);
            $head = User::where('id', $approval->approve_id)->first();

            $status = 'Approved';
        }

        $user = User::where('id', $approval->user_id)->first();

        $user->notify(new ApprovalNotification($approval, $head, $status));

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Approved Successfully",
            'color' => "#435ebe",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }

    public function clickdeclined($id) {
        $this->approving = Approval::find($id);
    }

    public function declined(){
        if ($this->approving->review_id == Auth::user()->id){
            Approval::where('id', $this->approving->id)->update([
                'review_status' => 2,
                'review_date' => Carbon::now(),
                'review_message' => $this->comment,
                'approve_status' => 2,
                'approve_date' => Carbon::now(),
            ]);
            $head = User::where('id', $this->approving->review_id)->first();
        } elseif ($this->approving->approve_id == Auth::user()->id){
            Approval::where('id', $this->approving->id)->update([
                'approve_status' => 2,
                'approve_date' => Carbon::now(),
                'approve_message' => $this->comment,
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
    }

    public function resetInput(){
        $this->view = false;
        $this->category = '';
        $this->user_id = '';
        $this->url = '';
        $this->approval = '';
        $this->userType = '';
        $this->approving = '';
        $this->comment = '';
    }

    public function closeModal(){
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }
}
