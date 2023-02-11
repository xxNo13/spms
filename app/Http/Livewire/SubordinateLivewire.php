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
    public $duration;
    public $userType;
    public $percentage;

    protected  $queryString = ['search'];

    public function viewed($user_id, $url, $userType){
        $this->user_id = $user_id;
        $this->url = $url;
        $this->view = true;
        $this->userType = $userType;

        if ($this->duration) {
            $this->percentage = Percentage::where('user_id', $user_id)
                ->where('type', 'ipcr')
                ->where('userType', $userType)
                ->where('duration_id', $this->duration->id)
                ->first();
        }
    }

    public function updated($property)
    {
        if ($property == 'search') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();

        if ($this->view) {
            $functs = Funct::all();
            $user = User::find($this->user_id);
            return view('components.individual-ipcr',[
                'functs' => $functs,
                'user' => $user,
                'url' => $this->url,
                'duration' => $this->duration,
                'userType' => $this->userType,
                'percentage' => $this->percentage,
                'number' => 1
            ]);
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
                'duration' => $this->duration
            ]);
        }
    }
}
