<?php

namespace App\Http\Livewire;

use App\Models\Target;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\Training;
use Livewire\WithPagination;
use App\Models\ScoreEquivalent;
use Illuminate\Support\Facades\Auth;

class RecommendationListLivewire extends Component
{
    use WithPagination;
    
    public $scoreEquivalent;

    public function render()
    {
        $trainings;
        $faculty = false;
        $staff = false;
        $assFaculty = false;
        $assStaff = false;
        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->scoreEquivalent = ScoreEquivalent::first();

        foreach (Auth::user()->account_types as $account_type) {
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
                    ->where('approve_status', 1)
                    ->where('user_id', Auth::user()->id)
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
                    ->where('approve_status', 1)
                    ->where('user_id', Auth::user()->id)
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
            $targets = [];
            if($duration) {
                foreach(auth()->user()->targets as $target){
                    $rating = $target->ratings()->where('user_id', auth()->user()->id)->first();
                    if (isset($rating) && $rating->average < $this->scoreEquivalent->sat_to) {
                        array_push($targets, $target);
                    }
                }
            }

            if(!empty($targets)) {
                $trainings = Training::query();

                foreach ($targets as $target) {
                    $results = preg_split('/\s+/', $target->target);
    
                    foreach ($results as $result) {
                        $trainings->orWhere('training_name', 'LIKE', '%' . $result . '%')
                        ->orWhere('keywords', 'LIKE', '%' . $result . '%');
                    }
                }
                $trainings = $trainings->distinct();
            } else {
                $trainings = Training::where('id', 0);
            }
        } else {
            $trainings = Training::where('id', 0);
            $targets = [];
        }

        return view('livewire.recommendation-list-livewire', [
            'trainings' => $trainings->paginate(25),
            'scoreEquivalent' => $this->scoreEquivalent,
            'targets' => $targets
        ]);
    }
}
