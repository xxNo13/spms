<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Funct;
use Livewire\Component;
use App\Models\Duration;
use App\Models\Percentage;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SubordinateLivewire extends Component
{
    use WithPagination;

    public $view = false;
    public $user_id;
    public $url;
    public $search;
    public $durationS;
    public $durationF;
    public $user_type;
    public $percentage;

    protected  $queryString = ['search'];

    public function viewed($user_id, $url, $user_type){
        $this->user_id = $user_id;
        $this->url = $url;
        $this->view = true;
        $this->user_type = $user_type;
    }

    public function updated($property)
    {
        if ($property == 'search') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $this->durationS = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->durationF = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->first();

        if ($this->view) {
            $functs = Funct::all();
            $user = User::find($this->user_id);
            
            foreach ($user->account_types as $account_type) {
                if (str_contains(strtolower($account_type->account_type), 'faculty')){

                    $this->percentage = Percentage::where('user_id', null)
                        ->where('type', 'ipcr')
                        ->where('user_type', $this->user_type)
                        ->where('duration_id', $this->durationF->id)
                        ->first();

                    return view('components.individual-ipcr',[
                        'functs' => $functs,
                        'user' => $user,
                        'url' => $this->url,
                        'duration' => $this->durationF,
                        'user_type' => $this->user_type,
                        'percentage' => $this->percentage,
                        'number' => 1
                    ]);
                }
                if (str_contains(strtolower($account_type->account_type), 'staff')){

                    $this->percentage = Percentage::where('user_id', $this->user_id)
                        ->where('type', 'ipcr')
                        ->where('user_type', $this->user_type)
                        ->where('duration_id', $this->durationS->id)
                        ->first();

                    return view('components.individual-ipcr',[
                        'functs' => $functs,
                        'user' => $user,
                        'url' => $this->url,
                        'duration' => $this->durationS,
                        'user_type' => $this->user_type,
                        'percentage' => $this->percentage,
                        'number' => 1
                    ]);
                }
            }
        } else {
            $searches = preg_split('/\s+/', $this->search);
            $query = User::query();
            $users = User::query();

            foreach (Auth::user()->offices()->get() as $office) {
            
                $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office) {
                    return $query->where('id', $office->id);
                });
    
                foreach ($office->child as $office) {
                    $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office) {
                        return $query->where('id', $office->id);
                    });
    
                    foreach ($office->child as $office) {
                        $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office) {
                            return $query->where('id', $office->id);
                        });
                    }
                }
            }

            if ($searches) {
                foreach ($searches as $search) {
                    if (str_contains('head', $search)) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orwhere('email', 'like', "%{$search}%")
                            ->orWhereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                                return $query->where('account_type', 'LIKE','%'.$search.'%');
                            })->orWhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                                return $query->where('office_abbr', 'LIKE','%'.$search.'%')
                                            ->orwhere('isHead', 1);
                            });
                    } else {
                        $query->where('name', 'like', "%{$search}%")
                            ->orwhere('email', 'like', "%{$search}%")
                            ->orWhereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                                return $query->where('account_type', 'LIKE','%'.$search.'%');
                            })->orWhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($search){
                                return $query->where('office_abbr', 'LIKE','%'.$search.'%');
                            });
                    }
                }
            }

            $results = collect();
            
            foreach ($users->distinct()->get() as $user) {
                foreach ($query->distinct()->get() as $que) {
                    if ($que->id === $user->id) {
                        $results->push($user);
                        break;
                    }
                }
            }

            // $users = User::where('name', 'like', '%'.$this->search.'%')->orderBy('name', 'ASC')->paginate(10);
            return view('livewire.subordinate-livewire',[
                'users' => $results->sortBy('name'),
                'duration' => $this->durationF
            ]);
        }
    }
}
