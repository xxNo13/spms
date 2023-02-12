<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Target;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\ScoreEquivalent;
use Illuminate\Support\Facades\Auth;

class RecommendedForTrainingLivewire extends Component
{
    public $lists;
    public $scoreEquivalent;
    public $users = [];

    public function render()
    {
        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->scoreEquivalent = ScoreEquivalent::first();

        foreach (Auth::user()->offices as $office) { 
            if ($office->office_abbr == 'PMO') {
                $pmo = true;
            }
            if ($office->office_abbr == 'HRMO') {
                $hrmo = true;
            }
        }

        $users = collect();

        if (isset($pmo) || isset($hrmo)) {
            $users = User::all();
        } else {
            foreach (Auth::user()->offices as $off) {
                foreach ($off->users as $user) {
                    $users->push($user);
                }
            }
        }

        foreach ($users as $user) {
            $faculty = false;
            $staff = false;
            $assFaculty = false;
            $assStaff = false;
            foreach ($user->account_types as $account_type) {
                if (str_contains(strtolower($account_type->account_type), 'faculty')){
                    $faculty = true;
                }
                if (str_contains(strtolower($account_type->account_type), 'staff')){
                    $staff = true;
                }
            }

            if ($faculty) {
                if ($duration) {
                    $assessF = Approval::orderBy('id', 'DESC')
                        ->where('name', 'assess')
                        ->where('review_status', 1)
                        ->where('approve_status', 1)
                        ->where('user_id', $user->id)
                        ->where('type', 'ipcr')
                        ->where('duration_id', $duration->id)
                        ->where('user_type', 'faculty')
                        ->first();
    
                    if (isset($assessF)) {
                        $assFaculty = true;
                    }
                }
            } else {
                $assFaculty = true;
            }
    
            if ($staff) {
                if ($duration) {
                    $assessS = Approval::orderBy('id', 'DESC')
                        ->where('name', 'assess')
                        ->where('review_status', 1)
                        ->where('approve_status', 1)
                        ->where('user_id', $user->id)
                        ->where('type', 'ipcr')
                        ->where('duration_id', $duration->id)
                        ->where('user_type', 'staff')
                        ->first();
    
                    if (isset($assessS)) {
                        $assStaff = true;
                    }
                }
            } else {
                $assStaff = true;
            }
            
            if ($assFaculty && $assStaff) {
                $targ = '';
                if($duration) {
                    foreach($user->targets as $target){
                        $rating = $target->ratings()->where('user_id', $user->id)->first();
                        if (isset($rating) && $rating->average < $this->scoreEquivalent->sat_to) {
                            array_push($this->users, $user);
                        }
                    }
                }
            }
        }
        return view('livewire.recommended-for-training-livewire', [
            'users' => $this->users
        ]);
    }
}
