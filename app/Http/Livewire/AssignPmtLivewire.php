<?php

namespace App\Http\Livewire;

use App\Models\Pmt;
use App\Models\User;
use App\Models\Office;
use Livewire\Component;

class AssignPmtLivewire extends Component
{
    public $users;
    public $faculty_users;
    public $staff_users;

    public $ids = [];
    public $planning;
    public $isHead;

    public function mount() {
        $this->users = User::where('id', '!=', 1)->get();

        $this->faculty_users = User::whereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query){
                            return $query->where('account_type', 'LIKE', '%faculty%');
                        })->where('id', '!=', 1)->get();

        $this->staff_users = User::whereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query){
                            return $query->where('account_type', 'LIKE', '%staff%');
                        })->where('id', '!=', 1)->get();

        foreach (Pmt::all() as $pmt) {
            $this->ids[$pmt->id] = $pmt->user_id;
        }   

        foreach (Pmt::where('isHead', 1)->get() as $pmt) {
            $this->isHead = $pmt->id;
        }
    }

    public function render()
    {
        return view('livewire.assign-pmt-livewire', [
            'pmts' => Pmt::all(),
        ]);
    }

    public function save() {
        foreach ($this->ids as $id => $user_id) {
            if ($user_id == "") {
                Pmt::where('id', $id)->update([
                    'user_id' => null,
                    'isHead' => 0
                ]);
            } else {
                Pmt::where('id', $id)->update([
                    'user_id' => $user_id,
                    'isHead' => 0
                ]);
            }
        }
        Pmt::where('id', $this->isHead)->update([
            'isHead' => 1
        ]);

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Updated Successfully",
            'color' => "#28ab55",
        ]);
    }
}
