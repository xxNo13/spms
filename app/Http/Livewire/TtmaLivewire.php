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
use Livewire\WithFileUploads;

class TtmaLivewire extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $users = [];
    public $subject;
    public $users_id;
    public $output;
    public $ttma_id;
    public $search;
    public $duration;
    public $message;
    public $selected;
    public $deadline;

    public $month_filter = '';
    public $filter = 'receive';

    public $file;
    public $itteration = 1;

    protected $rules = [
        'subject' => ['nullable', 'required_if:selected,assign'],
        'users_id' => ['nullable', 'required_if:selected,assign'],
        'output' => ['nullable', 'required_if:selected,assign'],
        'deadline' => ['nullable', 'required_if:selected,assign'],
        
        'message' => ['nullable', 'required_without_all:file'],
    ];

    protected $messages = [
        'subject.required_if' => 'The Subject cannot be empty.',
        'users_id.required_if' => 'Need to assign to a user.',
        'output.required_if' => 'The Output cannot be empty.',
        'deadline.required_if' => 'The Deadline cannot be empty.',

        'message.required_without_all' => 'Input message cannot be empty.',
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
        $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->first();
    }

    public function render()
    {  
        $search = $this->search;
        $ttmas = Ttma::query();
        $assignments = Ttma::query()->whereHas('users', function (\Illuminate\Database\Eloquent\Builder $query) {
            return $query->where('id', auth()->user()->id);
        });

        // if ($search) {
        //     $ttmas->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
        //             return $query->Where('subject', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('output', 'LIKE', '%' . $search . '%')
        //                 ->orwhereHas('users', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
        //                     return $query->where('name', 'LIKE', '%' . $search . '%');
        //                 });
        //             });
            
        //     $assignments->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
        //                 return $query->Where('subject', 'LIKE', '%' . $search . '%')
        //                     ->orWhere('output', 'LIKE', '%' . $search . '%')
        //                     ->orwhereHas('head', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
        //                     return $query->where('name', 'LIKE', '%' . $search . '%');
        //                 });
        //             });
        // }

        if ($this->month_filter != '') {
            return view('livewire.ttma-livewire', [
                'ttmas' => $ttmas->orderBy('deadline', 'ASC')
                        ->where('duration_id', $this->duration->id)
                        ->where('head_id', Auth::user()->id)
                        ->whereMonth('deadline', $this->month_filter)
                        ->paginate(10),

                'assignments' => $assignments->orderBy('deadline', 'ASC')
                                ->where('duration_id', $this->duration->id)
                                ->whereMonth('deadline', $this->month_filter)
                                ->paginate(10),
            ]);
        }

        return view('livewire.ttma-livewire', [
            'ttmas' => $ttmas->orderBy('deadline', 'ASC')
                    ->where('duration_id', $this->duration->id)
                    ->where('head_id', Auth::user()->id)
                    ->paginate(10),
            'assignments' => $assignments->orderBy('deadline', 'ASC')
                            ->where('duration_id', $this->duration->id)
                            ->paginate(10),
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
        
            foreach ($this->users_id as $user_id) {
                $user = User::find($user_id);

                $user->notify(new AssignmentNotification($ttma));
            }

            Ttma::where('id', $this->ttma_id)->update([
                'subject' => $this->subject,
                'output' => $this->output,
                'deadline' => $this->deadline,
            ]);

            $ttma = Ttma::find($this->ttma_id);

            $ttma->users()->sync($this->users_id);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        } else {
            $ttma = Ttma::create([
                'subject' => $this->subject,
                'output' => $this->output,
                'head_id' => Auth::user()->id,
                'deadline' => $this->deadline,
                'duration_id' => $this->duration->id
            ]);

            $ttma->users()->attach($this->users_id);

            foreach ($this->users_id as $user_id) {
                $user = User::find($user_id);

                $user->notify(new AssignmentNotification($ttma));
            }

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        }

        return redirect(request()->header('Referer'));
    }

    public function updatedPhoto()
    {
        $this->validate([
            'file' => 'mimes:pdf,docx,doc,xml,application/msword',
        ]);
    }

    public function message() {

        $this->validate();

        $ttma = Ttma::where('id', $this->ttma_id)->first();

        if ($ttma->head_id == auth()->user()->id) {
            
            $users = $ttma->users()->where('id', '!=', auth()->user()->id)->get();

            foreach ($users as $user) {
                $user->notify(new AssignmentNotification($ttma, 'Message'));
            }
        } else {
            $user = User::where('id', $ttma->head_id)->first();

            $user->notify(new AssignmentNotification($ttma, 'Message'));
        }

        $url = $this->file->store('document');

        if ($url) {
            Message::create([
                'user_id' => auth()->user()->id,
                'ttma_id' => $ttma->id,
                'message' => $url,
                'file_default_name' => $this->file->getClientOriginalName()
            ]);
        }

        if ($this->message) {
            Message::create([
                'user_id' => auth()->user()->id,
                'ttma_id' => $ttma->id,
                'message' => $this->message
            ]);
        }

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
    
        $users = $ttma->users()->where('id', '!=', auth()->user()->id)->get();

        foreach ($users as $user) {
            $user->notify(new AssignmentNotification($ttma));
        }

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
            $this->output = $data->output;
            $this->deadline = $data->deadline;

            $this->users_id = $data->users()->pluck('id')->toArray();
        }
    }

    public function delete()
    {
        $ttma = Ttma::where('id', $this->ttma_id)->first();

        $users = $ttma->users()->where('id', '!=', auth()->user()->id)->get();

        foreach ($users as $user) {
            foreach ($user->notifications as $notification) {
                if(isset($notification->data['ttma_id']) && $notification->data['ttma_id'] == $ttma->id){
                    $notification->delete();
                }
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
        $this->users_id = [];
        $this->output = '';
        $this->ttma_id = '';
        $this->message = '';
        $this->deadline = '';
        $this->file = null;
        $this->itteration++;
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
