<?php

namespace App\Http\Livewire;

use App\Models\Ttma;
use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Models\Duration;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AssignmentNotification;

class TtmaLivewire extends Component
{
    use WithPagination;

    public $users;
    public $subject;
    public $user_id;
    public $output;
    public $ttma_id;
    public $search;
    public $duration;
    public $message;
    public $selected;
    public $deadline;

    public $filter = 'receive';

    protected $rules = [
        'subject' => ['nullable', 'required_if:selected,assign'],
        'user_id' => ['nullable', 'required_if:selected,assign'],
        'output' => ['nullable', 'required_if:selected,assign'],
        'deadline' => ['nullable', 'required_if:selected,assign'],
        
        'message' => ['nullable', 'required_if:selected,message'],
    ];

    protected $messages = [
        'subject.required_if' => 'The Subject cannot be empty.',
        'user_id.required_if' => 'Need to assign to a user.',
        'output.required_if' => 'The Output cannot be empty.',
        'deadline.required_if' => 'The Deadline cannot be empty.',

        'message.required_if' => 'Input message cannot be empty.',
    ];

    protected  $queryString = ['search'];

    public function mount() {

        $users = User::query();

        foreach (Auth::user()->offices()->wherePivot('isHead', true)->get() as $office) {
            
            $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office) {
                return $query->where('id', $office->id)->where('isHead', false);
            })->where('id', '!=', Auth::user()->id);

            foreach ($office->child as $office) {
                $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office) {
                    return $query->where('id', $office->id);
                })->where('id', '!=', Auth::user()->id);

                foreach ($office->child as $office) {
                    $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office) {
                        return $query->where('id', $office->id);
                    })->where('id', '!=', Auth::user()->id);
                }
            }
        }
        
        $this->users = $users->distinct()->orderBy('name', 'ASC')->get();
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
    }

    public function render()
    {   
        $search = $this->search;
        $ttmas = Ttma::query();
        $assignments = Ttma::query();

        if ($search) {

            if($this->duration) {
                $ttmas->where(function ($query) use ($search) {
                    $query->whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                        return $query->where('name', 'LIKE', '%' . $search . '%');
                    })
                        ->orWhere('subject', 'LIKE', '%' . $search . '%')
                        ->orWhere('output', 'LIKE', '%' . $search . '%');
                })->where('duration_id', $this->duration->id)
                ->where('head_id', Auth::user()->id)
                ->get();
            
                
                $assignments->where(function ($query) use ($search) {
                    $query->whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                        return $query->where('name', 'LIKE', '%' . $search . '%');
                    })
                        ->orWhere('subject', 'LIKE', '%' . $search . '%')
                        ->orWhere('output', 'LIKE', '%' . $search . '%');
                })->where('duration_id', $this->duration->id)
                ->where('user_id', Auth::user()->id)
                ->get();
            } else {
                $ttmas->where(function ($query) use ($search) {
                    $query->whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                        return $query->where('name', 'LIKE', '%' . $search . '%');
                    })
                        ->orWhere('subject', 'LIKE', '%' . $search . '%')
                        ->orWhere('output', 'LIKE', '%' . $search . '%');
                })->where('head_id', Auth::user()->id)
                    ->get();

                $assignments->where(function ($query) use ($search) {
                    $query->whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                        return $query->where('name', 'LIKE', '%' . $search . '%');
                    })
                        ->orWhere('subject', 'LIKE', '%' . $search . '%')
                        ->orWhere('output', 'LIKE', '%' . $search . '%');
                })->where('user_id', Auth::user()->id)
                    ->get();
            }
        }

        if($this->duration) {
            return view('livewire.ttma-livewire', [
                'ttmas' => $ttmas->orderBy('created_at', 'DESC')
                        ->where('duration_id', $this->duration->id)
                        ->where('head_id', Auth::user()->id)
                        ->paginate(10),
                'assignments' => $assignments->orderBy('created_at', 'DESC')
                                ->where('user_id', Auth::user()->id)
                                ->where('duration_id', $this->duration->id)
                                ->paginate(10),
            ]);
        }
        return view('livewire.ttma-livewire', [
            'ttmas' => $ttmas->where('head_id', Auth::user()->id)->paginate(10),
            'assignments' => Ttma::orderBy('created_at', 'DESC')->paginate(10),
            'duration' => $this->duration
        ]);
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();

        if ($this->ttma_id) {
            $ttma = Ttma::where('id', $this->ttma_id)->first();

            $userOld = User::where('id', $ttma->user_id)->first();

            foreach ($userOld->notifications as $notification) {
                if(isset($notification->data['ttma_id']) && $notification->data['ttma_id'] == $ttma->id){
                    $notification->delete();
                }
            }
        
            $user = User::where('id', $this->user_id)->first();

            $user->notify(new AssignmentNotification($ttma));

            Ttma::where('id', $this->ttma_id)->update([
                'subject' => $this->subject,
                'user_id' => $this->user_id,
                'output' => $this->output,
                'deadline' => $this->deadline,
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        } else {
            $ttma = Ttma::create([
                'subject' => $this->subject,
                'user_id' => $this->user_id,
                'output' => $this->output,
                'head_id' => Auth::user()->id,
                'deadline' => $this->deadline,
                'duration_id' => $this->duration->id
            ]);

            $user = User::where('id', $this->user_id)->first();

            $user->notify(new AssignmentNotification($ttma));

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        }

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function message() {

        $this->validate();

        $ttma = Ttma::where('id', $this->ttma_id)->first();
    
        if ($ttma->user_id == auth()->user()->id) {
            $user = User::where('id', $ttma->head_id)->first();
        } else {
            $user = User::where('id', $ttma->user_id)->first();
        }

        $user->notify(new AssignmentNotification($ttma, 'Message'));

        Message::create([
            'user_id' => auth()->user()->id,
            'ttma_id' => $ttma->id,
            'message' => $this->message
        ]);

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Sent Successfully",
            'color' => "#435ebe",
        ]);
        $this->resetInput();
    }

    public function done()
    {
        $ttma = Ttma::where('id', $this->ttma_id)->first();
        
        $ttma->update([
            'remarks' => 'Done'
        ]);

        $user = User::where('id', $ttma->user_id)->first();

        $user->notify(new AssignmentNotification($ttma));

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Mark Assignment as completed",
            'color' => "#435ebe",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function select($select, $ttma_id = null, $category = null)
    {
        $this->selected = $select;
        $this->ttma_id = $ttma_id;

        if ($category == 'edit') {

            $data = Ttma::find($ttma_id);

            $this->subject = $data->subject;
            $this->user_id = $data->user_id;
            $this->output = $data->output;
            $this->message = $data->message;
            $this->deadline = $data->deadline;
        }
    }

    public function delete()
    {
        $ttma = Ttma::where('id', $this->ttma_id)->first();

        $user = User::where('id', $ttma->user_id)->first();

        foreach ($user->notifications as $notification) {
            if(isset($notification->data['ttma_id']) && $notification->data['ttma_id'] == $ttma->id){
                $notification->delete();
            }
        }
        
        $ttma->delete();

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function resetInput()
    {
        $this->subject = '';
        $this->user_id = '';
        $this->output = '';
        $this->ttma_id = '';
        $this->message = '';
        $this->deadline = '';
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
