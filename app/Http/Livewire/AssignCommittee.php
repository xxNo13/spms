<?php

namespace App\Http\Livewire;

use App\Models\Committee;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AssignCommittee extends Component
{
    use WithPagination;

    public $users;
    public $search;

    public $rc_id;
    public $type;
    public $name;
    public $committee_type;
    public $user_id;

    protected  $queryString = ['search'];

    public function updated($property)
    {
        if ($property == 'search') {
            $this->resetPage();
        }
    }
    
    public function mount() {
        $this->users = User::all();
    }

    public function render()
    {
        $committees = Committee::query();

        if ($this->search) {
            $search = $this->search;

            $committees->where(function ($query) use ($search){
                    return $query->whereHas('user', function(\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    });
                })->orwhere('name', 'like', "%{$search}%")
                ->orWhere('type', 'LIKE', "%{$search}%");
        }
        $committees->distinct();

        return view('livewire.assign-committee', [
            'committees' => $committees->get(),
        ]);
    }

    public function select($type, $id = null) {
        $this->type = $type;
        if ($id) {
            $this->rc_id = $id;
            $rc = Committee::find($id);

            $this->name = $rc->name;
            $this->user_id = $rc->user_id;
            $this->committee_type = $rc->committee_type;
        }
    }

    public function save($category) {
        if ($category == 'add') {
            Committee::create([
                'name' => $this->name,
                'type' => $this->type,
                'user_id' => $this->user_id,
                'committee_type' => $this->committee_type,
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        }
        if ($category == 'edit') {
            Committee::where('id', $this->rc_id)->update([
                'name' => $this->name,
                'user_id' => $this->user_id,
                'committee_type' => $this->committee_type
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        }
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }

    public function delete() {
        Committee::where('id', $this->rc_id)->delete();

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }

    public function resetInput() {
        $this->name = '';
        $this->user_id = '';
        $this->search = '';
        $this->type = '';
    }

    public function closeModal(){
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }
}
