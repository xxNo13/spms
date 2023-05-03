<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Funct;
use App\Models\Office;
use App\Models\Rating;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\Percentage;
use App\Models\scoreReview;
use App\Models\Committee;
use App\Notifications\ApprovalNotification;

class ReviewingIpcr extends Component
{
    public $score_review;
    public $user_id;
    public $url;
    public $user_type;
    public $view;
    public $duration;
    public $percentage;
    public $selected;
    public $rating_id;
    public $target_id;
    public $selectedTarget;
    public $targetOutput;
    public $output_finished;
    public $accomplishment;
    public $quality;
    public $timeliness;
    public $efficiency;
    public $selectedTargetId;

    public $review_id;
    public $approve_id;

    public $committee_status;

    public $teaching_sub_funct;
    public $non_teaching_sub_funct;

    public $hr = false;
    
    public function viewed($id, $url, $committee_type){
        $this->score_review = ScoreReview::find($id);
        $this->user_id = $this->score_review->user_id;
        $this->url = $url;
        $this->user_type = $this->score_review->type;
        $this->view = true;

        $user = User::find($this->user_id);

        $this->committee_status = ScoreReview::where($committee_type, 1)->first();

        if ($this->score_review->type == 'staff') {
            $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->first();
            
            $this->percentage = Percentage::where('user_id', $this->user_id)
                ->where('type', 'ipcr')
                ->where('user_type', 'staff')
                ->where('duration_id', $this->duration->id)
                ->first();
        } elseif ($this->score_review->type == 'faculty') {
            $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->first();

            $this->percentage = Percentage::where('type', 'ipcr')
                ->where('user_type', 'faculty')
                ->where('duration_id', $this->duration->id)
                ->first();
                

            $this->teaching_sub_funct = $user->sub_functs()->where('funct_id', 1)->where('duration_id', $this->duration->id)->where('type', 'ipcr')->where('user_type', 'faculty')->first();
            $this->non_teaching_sub_funct = $user->sub_functs()->where('funct_id', 1)->where('duration_id', $this->duration->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('id', '!=', $this->teaching_sub_funct->id)->first();
        }
        
        foreach (auth()->user()->offices as $office) {
            if (str_contains(strtolower($office->office_name), 'hr') || str_contains(strtolower($office->office_name), 'hr')) {
                $this->hr = true;
                break;
            }
        }
    }

    public function render()
    {
        if ($this->view) {
            $functs = Funct::all();
            $user = User::find($this->user_id);
            return view('components.individual-ipcr',[
                'functs' => $functs,
                'user' => $user,
                'number' => 1,
            ]);
        }

        $eval_committees = Committee::where('committee_type', 'eval_committee')->pluck('user_id')->toArray();
        $review_committees = Committee::where('committee_type', 'review_committee')->pluck('user_id')->toArray();
        $reviewing_scores = ScoreReview::all();

        return view('livewire.reviewing-ipcr', [
            'reviewing_scores' => $reviewing_scores,
            'eval_committees' => $eval_committees,
            'review_committees' => $review_committees,
        ]);
    }

    public function rating($target_id = null, $rating_id = null){
        $user = User::find($this->user_id);

        $this->selected = 'rating';
        $this->rating_id = $rating_id;
        $this->target_id = $target_id;
        if ($target_id) {
            $this->selectedTarget = $user->targets()->where('id', $target_id)->first();
            $this->targetOutput = $this->selectedTarget->pivot->target_output;
        }
    }

    public function editRating($rating_id){
        $user = User::find($this->user_id);
        
        $this->selected = 'rating';
        $this->rating_id = $rating_id;

        $rating = $user->ratings()->where('id', $rating_id)->first();

        $this->selectedTarget = $user->targets()->where('id', $rating->target_id)->first();
        $this->targetOutput = $this->selectedTarget->pivot->target_output;
        
        $this->output_finished = $rating->output_finished;
        $this->accomplishment = $rating->accomplishment;
        $this->quality = $rating->quality;
        $this->timeliness = $rating->timeliness;
    }

    public function saveRating($category){

        
        $divisor = 0;
        
        $standard = $this->selectedTarget->standards()->first();

        if ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) {
            if ($standard->eff_5) {
                $eff_5 = strtok($standard->eff_5, '%');
            }
            if ($standard->eff_4) {
                $eff_4 = strtok($standard->eff_4, '%');
            }
            if ($standard->eff_3) {
                $eff_3 = strtok($standard->eff_3, '%');
            }
            if ($standard->eff_2) {
                $eff_2 = strtok($standard->eff_2, '%');
            }

            if (str_contains($standard->eff_5, '%')) {
                $output_percentage = $this->output_finished/$this->targetOutput * 100;
            } else {
                $output_percentage = $this->output_finished;
            }
            
            if (isset($eff_5) && $output_percentage >= (float)$eff_5) {
                $efficiency = 5;
            } elseif (isset($eff_4) && $output_percentage >= (float)$eff_4) {
                $efficiency = 4;
            } elseif (isset($eff_3) && $output_percentage >= (float)$eff_3) {
                $efficiency = 3;
            } elseif (isset($eff_2) && $output_percentage >= (float)$eff_2) {
                $efficiency = 2;
            } else {
                $efficiency = 1;
            }
        }

        if ($this->quality == '') {
            if ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1){
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'quality' => ['Quality cannot be null.'],
                    ]);
                    throw $error;
            } else {
                $this->quality = null;
            }
        }
        if ($this->timeliness == '') {
            if ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1){
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'timeliness' => ['Timeliness cannot be null.'],
                    ]);
                    throw $error;
            } else {
                $this->timeliness = null;
            }
        }

        if(!$efficiency){
            $divisor++;
        }
        if(!$this->quality){
            $divisor++;
        }
        if(!$this->timeliness){
            $divisor++;
        }
        $number = ((int)$efficiency + (int)$this->quality + (int)$this->timeliness) / (3 - $divisor);
        $average = number_format((float)$number, 2, '.', '');

        Rating::where('id', $this->rating_id)->update([
            'output_finished' => $this->output_finished,
            'accomplishment' => $this->accomplishment,
            'efficiency' => $efficiency,
            'quality' => $this->quality,
            'timeliness' => $this->timeliness,
            'average' => $average,
        ]);

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Updated Successfully",
            'color' => "#28ab55",
        ]);
        

        $this->output_finished = '';
        $this->efficiency = '';
        $this->quality = '';
        $this->timeliness = '';
        $this->accomplishment = '';
        $this->dispatchBrowserEvent('close-modal');
    }

    public function approved($id) {
        $score_review = ScoreReview::where('id', $id)->first();
        
        $approval = collect([
            'id' => $score_review->id,
            'type' => 'ipcr',
            'user_type' => $score_review->type
        ]);
        $user = User::find($score_review->user_id);

        if ($score_review->prog_chair_id == auth()->user()->id && !$score_review->prog_chair_status) {
            ScoreReview::where('id', $id)->update([
                'prog_chair_status' => 1,
            ]);

            if ($score_review->designated_id) {
                $reviewer = User::where('id', $score_review->designated_id)->first();
                $reviewer->notify(new ApprovalNotification($approval, $user, 'Submitting', 'score-review'));
            } else {
                
                $offices = Office::where('office_abbr', 'LIKE', '%hr%')->orwhere('office_name', 'LIKE', '%hr%')->get();

                
                $users = User::query();
                foreach ($offices as $office) {
                    foreach ($office->users as $office_user) {
                        $users->where('id', $office_user->id);
                    }
                }
    
                foreach ($users->get() as $user_hr) {
                    
                    $user_hr->notify(new ApprovalNotification($approval, $user, 'Submitting', 'score-review'));
                }
            }

        } elseif ($score_review->designated_id == auth()->user()->id && !$score_review->designated_status) {
            ScoreReview::where('id', $id)->update([
                'designated_status' => 1,
            ]);

            $offices = Office::where('office_abbr', 'LIKE', '%hr%')->orwhere('office_name', 'LIKE', '%hr%')->get();

            $users = User::query();
            foreach ($offices as $office) {
                $users->whereHas('offices', function (\Illuminate\Database\Eloquent\Builder $query) use ($office) {
                    return $query->where('office_id', $office->id);
                });
            }

            foreach ($users->get() as $user_hr) {
                
                $user_hr->notify(new ApprovalNotification($approval, $user, 'Submitting', 'score-review'));
            }

        } elseif (!$score_review->hr_status) {
            ScoreReview::where('id', $id)->update([
                'hr_status' => 1,
            ]);

            $users = User::whereHas('committee', function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->where('committee_type', 'eval_committee');
            });

            foreach ($users->get() as $user_eval) {
                
                $user_eval->notify(new ApprovalNotification($approval, $user, 'Submitting', 'score-review'));
            }

        } elseif (!$score_review->eval_committee_status) {
            ScoreReview::where('id', $id)->update([
                'eval_committee_status' => 1,
            ]);

            $users = User::whereHas('committee', function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->where('committee_type', 'review_committee');
            });

            foreach ($users->get() as $user_review) {
                
                $user_review->notify(new ApprovalNotification($approval, $user, 'Submitting', 'score-review'));
            }

        } elseif (!$score_review->review_committee_status) {
            ScoreReview::where('id', $id)->update([
                'review_committee_status' => 1,
            ]);
        }

        $score_review = ScoreReview::where('id', $id)->first();
        
        $user = User::where('id', $score_review->user_id)->first();

        $approval = collect([
            'id' => $score_review->id,
            'type' => 'ipcr',
            'user_type' => $score_review->type,
        ]);

        $user->notify(new ApprovalNotification($approval, auth()->user(), 'Reviewed'));

        if ($score_review->review_committee_status) {
            $user = User::find($score_review->user_id);

            $depths = [];
            $highestOffice = [];

            foreach($user->offices as $office) {
                $depths[$office->id] = $office->getDepthAttribute();
            }

            foreach ($depths as $id => $depth) {
                if (min($depths) == $depth) {
                    $highestOffice[$id] = $depth;
                }
            }

            if ($this->user_type == 'staff') {
                foreach ($highestOffice as $id => $value) {

                    $office = Office::find($id);
        
                    if ($user->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                        $this->review_id = $office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                        
                        $parent_office = Office::where('id', $office->parent_id)->first();
                        if ($parent_office) {
                            $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                        }else {
                            $this->approve_id = $this->review_id;
                        }
                    } else {
                        $office = Office::where('id', $office->parent_id)->first();
                        $this->review_id = $office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                    
                        $parent_office = Office::where('id', $office->parent_id)->first();
                        if ($parent_office) {
                            $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                        }else {
                            $this->approve_id = $this->review_id;
                        }
                    }
        
                    if (!$this->review_id || !$this->approve_id) {
                        return $this->dispatchBrowserEvent('toastify', [
                            'message' => "No Head Found!",
                            'color' => "#f3616d",
                        ]);
                    }
        
                    $approval = Approval::create([
                        'name' => 'assess',
                        'user_id' => $user->id,
                        'approve_id' => $this->approve_id,
                        'type' => 'ipcr',
                        'user_type' => 'staff',
                        'duration_id' => $this->duration->id
                    ]);
                
                    $approve = $approval;
                    
                    $approve->reviewers()->attach([$this->review_id]);
                    
                    $reviewer = User::where('id', $this->review_id)->first();
                    $approver = User::where('id', $this->approve_id)->first();
            
                    $reviewer->notify(new ApprovalNotification($approval, $user, 'Submitting'));
                    $approver->notify(new ApprovalNotification($approval, $user, 'Submitting'));
        
                    $this->dispatchBrowserEvent('toastify', [
                        'message' => "Submitted Successfully",
                        'color' => "#435ebe",
                    ]);
        
                    $this->mount();

                    return redirect(request()->header('Referer'));
                }
            } elseif ($this->user_type == 'faculty') {
                $review_ids = [];

                foreach ($user->offices()->pluck('id')->toArray() as $id) {
                    $office = Office::find($id);

                    if ($user->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                        if ($office->users()->wherePivot('isHead', 1)->pluck('id')->first()) {
                            array_push($review_ids, $office->users()->wherePivot('isHead', 1)->pluck('id')->first());
                        }
                    } elseif ($user->offices()->where('id', $id)->first()->pivot->isHead) {
                        $parent_office = Office::where('id', $office->parent_id)->first();
                        if ($parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first()) {
                            array_push($review_ids, $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first());
                        }
                    }
                }

                if (count($highestOffice) > 1) {
                    $numberOfTarget = [];
                    $x = 0;
                    foreach ($user->sub_functs()->where('user_type', 'faculty')->where('duration_id', $this->duration->id)->where('funct_id', 1)->get() as $sub_funct) {
                        $numberOfTarget[$x] = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->pluck('value')->first();
                        $x++;
                    }

                    if ((isset($numberOfTarget[0]) && !isset($numberOfTarget[1])) || ($numberOfTarget[0] > $numberOfTarget[1])) {
                        foreach ($highestOffice as $id => $value) {

                            $office = Office::find($id);

                            if (str_contains(strtolower($office->office_name), 'dean')) {
                                if ($user->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                                    
                                    $parent_office = Office::where('id', $office->parent_id)->first();
                                    if ($parent_office) {
                                        $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                                    }else {
                                        $this->approve_id = $review_ids[0];
                                    }
                                } else {
                                    $office = Office::where('id', $office->parent_id)->first();
                                
                                    $parent_office = Office::where('id', $office->parent_id)->first();
                                    if ($parent_office) {
                                        $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                                    }else {
                                        $this->approve_id = $review_ids[0];
                                    }
                                }
                            }
                        }
                    } elseif ($numberOfTarget[0] <= $numberOfTarget[1]) {
                        foreach ($highestOffice as $id => $value) {

                            $office = Office::find($id);

                            if (!str_contains(strtolower($office->office_name), 'dean')) {
                                if ($user->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                                    
                                    $parent_office = Office::where('id', $office->parent_id)->first();
                                    if ($parent_office) {
                                        $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                                    }else {
                                        $this->approve_id = $review_ids[0];
                                    }
                                } else {
                                    $office = Office::where('id', $office->parent_id)->first();
                                
                                    $parent_office = Office::where('id', $office->parent_id)->first();
                                    if ($parent_office) {
                                        $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                                    }else {
                                        $this->approve_id = $review_ids[0];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach ($highestOffice as $id => $value) {

                        $office = Office::find($id);
            
                        if ($user->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                            
                            $parent_office = Office::where('id', $office->parent_id)->first();
                            if ($parent_office) {
                                $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                            }else {
                                $this->approve_id = $review_ids[0];
                            }
                        } else {
                            $office = Office::where('id', $office->parent_id)->first();
                        
                            $parent_office = Office::where('id', $office->parent_id)->first();
                            if ($parent_office) {
                                $this->approve_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                            }else {
                                $this->approve_id = $review_ids[0];
                            }
                        }
                    }
                }

                if (empty($review_ids) || !$this->approve_id) {
                    return $this->dispatchBrowserEvent('toastify', [
                        'message' => "No Head Found!",
                        'color' => "#f3616d",
                    ]);
                }

                $approval = Approval::create([
                    'name' => 'assess',
                    'user_id' => $user->id,
                    'approve_id' => $this->approve_id,
                    'type' => 'ipcr',
                    'user_type' => 'faculty',
                    'duration_id' => $this->duration->id
                ]);

                $approve = $approval;
                
                $approve->reviewers()->attach($review_ids);
                
                if (count($review_ids) > 1) {
                    foreach ($review_ids as $id) {
                        $reviewer = User::find($id);
                        $reviewer->notify(new ApprovalNotification($approval, $user, 'Submitting'));
                    }
                } else {
                    $reviewer = User::where('id', $review_ids[0])->first();
                    $reviewer->notify(new ApprovalNotification($approval, $user, 'Submitting'));
                }
                
                $approver = User::where('id', $this->approve_id)->first();
                $approver->notify(new ApprovalNotification($approval, $user, 'Submitting'));

                $this->dispatchBrowserEvent('toastify', [
                    'message' => "Submitted Successfully",
                    'color' => "#435ebe",
                ]);

                $this->mount();
            }
        }
        
        return redirect(request()->header('Referer'));
    }

    public function resetInput() {
        $this->output_finished = '';
        $this->efficiency = '';
        $this->quality = '';
        $this->timeliness = '';
        $this->accomplishment = '';
    }

    public function closeModal(){
        $this->dispatchBrowserEvent('close-modal'); 
    }
}
