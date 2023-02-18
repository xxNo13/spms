<?php

namespace App\Http\Livewire;

use App\Models\Pmt;
use App\Models\User;
use App\Models\Office;
use Livewire\Component;

class AssignPmtLivewire extends Component
{
    public $vice_users = [];
    public $finance_users = [];
    public $planning_users = [];
    public $resource_users = [];
    public $evaluation_users = [];
    public $faculty_users;
    public $staff_users;

    public $ids = [];
    public $planning;
    public $isHead;

    public function mount() {
        $vice = [];
        $finance = [];
        $planning = [];
        $resource = [];
        $evaluation = [];

        $viceOffice = Office::where('office_name', 'LIKE', '% vice%')->get();
        foreach ($viceOffice as $office) {
            $vice_users = User::whereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office){
                        return $query->where('id', $office->id);
                    });
            foreach ($vice_users->get() as $vice_user) {            
                array_push($vice, $vice_user);
            }
        }
        $this->vice_users = array_unique($vice);

        $financeOffice = Office::where('office_name', 'LIKE', '%finance%')->get();
        foreach ($financeOffice as $office) {
            $finance_users = User::whereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office){
                        return $query->where('id', $office->id);
                    });
            foreach ($finance_users->get() as $finance_user) {            
                array_push($finance, $finance_user);
            }
        }
        $this->finance_users = array_unique($finance);

        $planningOffice = Office::where('office_name', 'LIKE', '%planning%')->get();
        foreach ($planningOffice as $office) {
            $planning_users = User::whereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office){
                        return $query->where('id', $office->id);
                    });
            foreach ($planning_users->get() as $planning_user) {            
                array_push($planning, $planning_user);
            }
        }
        $this->planning_users = array_unique($planning);

        $resourceOffice = Office::where('office_name', 'LIKE', '%resource manage%')->get();
        foreach ($resourceOffice as $office) {
            $resource_users = User::whereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office){
                        return $query->where('id', $office->id);
                    });
            foreach ($resource_users->get() as $resource_user) {            
                array_push($resource, $resource_user);
            }
        }
        $this->resource_users = array_unique($resource);

        $evaluationOffice = Office::where('office_name', 'LIKE', '%evaluation%')->get();
        foreach ($evaluationOffice as $office) {
            $evaluation_users = User::whereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office){
                        return $query->where('id', $office->id);
                    });
            foreach ($evaluation_users->get() as $evaluation_user) {            
                array_push($evaluation, $evaluation_user);
            }
        }
        $this->evaluation_users = array_unique($evaluation);

        $this->faculty_users = User::whereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query){
                            return $query->where('account_type', 'LIKE', '%faculty%');
                        })->get();

        $this->staff_users = User::whereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query){
                            return $query->where('account_type', 'LIKE', '%staff%');
                        })->get();

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
