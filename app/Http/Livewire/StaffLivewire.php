<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Funct;
use App\Models\Office;
use App\Models\Output;
use App\Models\Rating;
use App\Models\Target;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\SubFunct;
use App\Models\Committee;
use App\Models\PrintInfo;
use App\Models\Suboutput;
use App\Models\IpcrReview;
use App\Models\Percentage;
use App\Models\ScoreReview;
use Livewire\WithPagination;
use App\Models\SubPercentage;
use App\Models\ScoreEquivalent;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApprovalNotification;

class StaffLivewire extends Component
{
    use WithPagination;

    public $selected = 'output';
    public $approval;
    public $approvalStandard;
    public $assess;
    public $review_user = [];
    public $approve_user;

    public $percent = [];
    public $sub_percent = [];

    public $funct_id;
    public $sub_funct;
    public $sub_funct_id;
    public $output;
    public $output_id;
    public $suboutput;
    public $subput;
    public $target;
    public $target_id;
    public $target_output;

    public $review_id;
    public $approve_id;
    public $highestOffice;
    
    public $rating_id;
    public $selectedTarget;
    public $output_finished;
    public $efficiency;
    public $quality;
    public $timeliness;
    public $accomplishment;

    public $targetOutput;

    public $filter = '';

    public $hasTargetOutput = false;
    public $hasRating = false;

    public $print;

    public $hasMultipleRating;

    public $printInfos = [];

    public $targetFiles;
    public $files = [];
    public $itteration = 1;

    protected $listeners = ['percentage', 'resetIntput'];

    protected $rules = [
        'percent.core' => ['required_if:selected,percent'],
        'percent.strategic' => ['required_if:selected,percent'],
        'percent.support' => ['required_if:selected,percent'],

        'sub_funct' => ['required_if:selected,sub_funct'],
        'output' => ['required_if:selected,output'],
        'output_id' => ['nullable', 'required_if:selected,output_id'],
        'suboutput' => ['required_if:selected,suboutput'],
        'subput' => ['nullable', 'required_if:selected,target_id'],
        'target' => ['required_if:selected,target'],
        'target_output' => ['nullable', 'required_if:selected,target_output', 'numeric'],

        'output_finished' => ['nullable', 'required_if:selected,rating', 'numeric'],
        'accomplishment' => ['required_if:selected,rating'],

        'files' => ['nullable', 'required_if:selected,file'],
    ];

    protected $messages = [
        'percent.core.required_if' => 'Core Percentage cannot be null',
        'percent.strategic.required_if' => 'Strategic Percentage cannot be null',
        'percent.support.required_if' => 'Support Percentage cannot be null',

        'sub_funct.required_if' => 'Sub Function cannot be null',
        'output.required_if' => 'Output cannot be null',
        'output_id.required_if' => 'Output cannot be null',
        'suboutput.required_if' => 'Suboutput cannot be null',
        'subput.required_if' => 'Suboutput/Output cannot be null',
        'target.required_if' => 'Target cannot be null',
        'target_output.required_if' => 'Target Output cannot be null',
        'target_output.numeric' => 'Target Output should be a number.',

        'output_finished.required_if' => 'Output Finished cannot be null.',
        'output_finished.numeric' => 'Output Finished should be a number.',
        'accomplishment.required_if' => 'Actual Accomplishment cannot be null.',

        'files.required_if' => 'File upload cannot be null.'
    ];
    
    public function updated($property)
    {
        $this->validateOnly($property);
    }


    public function mount() {
        $this->auth = Auth::user()->load('approvals', 'offices');
        $this->scoreEquivalent = ScoreEquivalent::first();
        $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'ipcr')->where('user_type', 'staff')->where('user_id', auth()->user()->id)->where('duration_id', $this->duration->id)->first();
            $this->sub_percentages = SubPercentage::where('type', 'ipcr')->where('user_type', 'staff')->where('user_id', auth()->user()->id)->where('duration_id', $this->duration->id)->get();

            $this->approval = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'staff')->first();
            $this->approvalStandard = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'standard')->where('duration_id', $this->duration->id)->where('user_type', 'staff')->where('approve_status', 1)->first();
            $this->assess = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'staff')->first();
            if ($this->assess) {
                $x = 0;
                foreach ($this->assess->reviewers as $reviewer) {
                    if ($reviewer->pivot->review_message) {
                        $this->review_user[$x]['name'] = $reviewer->name;
                        $this->review_user[$x]['message'] = $reviewer->pivot->review_message;
                        $x++;
                    }
                }

                $this->approve_user['name'] = User::where('id', $this->assess->approve_id)->pluck('name')->first();
                $this->approve_user['message'] = $this->assess->approve_message;
            } elseif ($this->approval) {
                $x = 0;
                foreach ($this->approval->reviewers as $reviewer) {
                    if ($reviewer->pivot->review_message) {
                        $this->review_user[$x]['name'] = $reviewer->name;
                        $this->review_user[$x]['message'] = $reviewer->pivot->review_message;
                        $x++;
                    }
                }

                $this->approve_user['name'] = User::where('id', $this->approval->approve_id)->pluck('name')->first();
                $this->approve_user['message'] = $this->approval->approve_message;
            }
        }

        $depths = [];

        foreach(auth()->user()->offices as $office) {
            $depths[$office->id] = $office->getDepthAttribute();
        }

        foreach ($depths as $id => $depth) {
            if (min($depths) == $depth) {
                $this->highestOffice[$id] = $depth;
            }
        }

    }

    public function render()
    {
        foreach (auth()->user()->targets as $target) {
            if (($target->suboutput_id && $target->suboutput->output->user_type == 'staff') || ($target->output_id && $target->output->user_type == 'staff')) {
                if (!isset($target->pivot->target_output)) {
                    $this->hasTargetOutput = false;
                    break;
                } else {
                    $this->hasTargetOutput = true;
                }
            }
        }

        foreach (auth()->user()->targets as $target) {
            if (($target->suboutput_id && $target->suboutput->output->user_type == 'staff') || ($target->output_id && $target->output->user_type == 'staff')) {
                if (count($target->ratings) > 0) {
                    foreach ($target->ratings as $rating) {
                        if ($rating->user_id == auth()->user()->id) {
                            $this->hasRating = true;
                            break;
                        } else {
                            $this->hasRating = false;
                        }
                    }
                    if (!$this->hasRating) {
                        break;
                    }
                } else {
                    $this->hasRating = false;
                    break;
                }
            }
        }

        return view('livewire.staff-livewire', [
            'functs' => Funct::paginate(1)
        ]);
    }

    /////////////////////////// RATING OF IPCR ///////////////////////////

    public function rating($target_id = null, $rating_id = null){
        $this->selected = 'rating';
        $this->rating_id = $rating_id;
        $this->target_id = $target_id;
        if ($target_id) {
            $this->selectedTarget = auth()->user()->targets()->where('id', $target_id)->first();
            $this->targetOutput = $this->selectedTarget->pivot->target_output;
        }
    }

    public function editRating($rating_id){
        $this->selected = 'rating';
        $this->rating_id = $rating_id;

        $rating = auth()->user()->ratings()->where('id', $rating_id)->first();

        $this->selectedTarget = auth()->user()->targets()->where('id', $rating->target_id)->first();
        $this->targetOutput = $this->selectedTarget->pivot->target_output;
        
        $this->output_finished = $rating->output_finished;
        $this->accomplishment = $rating->accomplishment;
        $this->quality = $rating->quality;
        $this->timeliness = $rating->timeliness;
    }

    public function saveRating($category){

        $this->validate();

        if ($category == 'add') {
            $divisor = 0;
            $efficiency = null;
            
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

            Rating::create([
                'output_finished' => $this->output_finished,
                'accomplishment' => $this->accomplishment,
                'efficiency' => $efficiency,
                'quality' => $this->quality,
                'timeliness' => $this->timeliness,
                'average' => $average,
                'remarks' => 'Done',
                'target_id' => $this->target_id,
                'duration_id' => $this->duration->id,
                'user_id' => auth()->user()->id
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        } elseif ($category == 'edit') {
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
        }
        
        $this->mount();
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    /////////////////////////// RATING OF IPCR END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// SUBMITION OF IPCR ///////////////////////////

    
    public function submit($type) {

        $this->selected = 'submition';

        if ($type == 'approval') {
            foreach ($this->highestOffice as $id => $value) {

                $office = Office::find($id);
    
                if (auth()->user()->offices()->where('id', $id)->first()->pivot->isHead == 0) {
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
                    'name' => $type,
                    'user_id' => auth()->user()->id,
                    'approve_id' => $this->approve_id,
                    'type' => 'ipcr',
                    'user_type' => 'staff',
                    'duration_id' => $this->duration->id
                ]);
            
                $approve = $approval;
                
                $approve->reviewers()->attach([$this->review_id]);
                
                $reviewer = User::where('id', $this->review_id)->first();
                $approver = User::where('id', $this->approve_id)->first();
        
                $reviewer->notify(new ApprovalNotification($approval, auth()->user(), 'Submitting'));
                $approver->notify(new ApprovalNotification($approval, auth()->user(), 'Submitting'));
    
                $this->dispatchBrowserEvent('toastify', [
                    'message' => "Submitted Successfully",
                    'color' => "#435ebe",
                ]);
    
                $this->mount();
                return;
            }
        } elseif ($type == 'assess') {
            foreach ($this->highestOffice as $id => $value) {

                $office = Office::find($id);
    
                if (auth()->user()->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                    $this->review_id = $office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                } else {
                    $office = Office::where('id', $office->parent_id)->first();
                    $this->review_id = $office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                }
                
                $prog_chair_id = $office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                
                if (auth()->user()->id == $prog_chair_id) {
                    $parent_office = Office::find($office->parent_id);
                    $prog_chair_id = $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first();
                }

                $score_review = ScoreReview::create([
                    'type' => 'staff',
                    'user_id' => auth()->user()->id,
                    'prog_chair_id' => $prog_chair_id,
                    'duration_id' => $this->duration->id
                ]);

                $approval = collect([
                    'id' => $score_review->id,
                    'type' => 'ipcr',
                    'user_type' => 'staff'
                ]);
                
                $reviewer = User::where('id', $prog_chair_id)->first();
                $reviewer->notify(new ApprovalNotification($approval, auth()->user(), 'Submitting', 'score-review'));


                $this->dispatchBrowserEvent('toastify', [
                    'message' => "Submitted Successfully",
                    'color' => "#435ebe",
                ]);

                $this->mount();
                return;
            }
        }
    }

    /////////////////////////// SUBMITION OF IPCR END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// SUBFUNCTION/OUTPUT/SUBOUTPUT/TARGET CONFIGURATION ///////////////////////////

    public function selectIpcr($type, $id, $category = null) {
        $this->selected = $type;
        switch($type) {
            case 'sub_funct':
                $this->sub_funct_id = $id;
                if ($category) {
                    $data = SubFunct::find($id);

                    $this->sub_funct = $data->sub_funct;
                }
                break; 
            case 'output':
                $this->output_id = $id;
                if ($category) {
                    $data = Output::find($id);

                    $this->output = $data->output;
                }
                break; 
            case 'suboutput':
                $this->suboutput_id = $id;
                if ($category) {
                    $data = Suboutput::find($id);

                    $this->suboutput = $data->suboutput;
                }
                break; 
            case 'target':
                $this->target_id = $id;
                if ($category) {
                    $data = Target::find($id);

                    $this->target = $data->target;
                }
                break;
            case 'target_output':
                $this->target_id = $id;
                if ($category) {
                    $data = auth()->user()->targets()->where('id', $id)->first();

                    $this->target_output = $data->pivot->target_output;
                }
                break; 
        }
    }

    public function saveIpcr() {

        $this->validate();

        switch (str_replace(url('/'), '', url()->previous())) {
            case '/ipcr/staff':
                $this->funct_id = 1;
                $code = 'CF';
                break;
            case '/ipcr/staff?page=2':
                $this->funct_id = 2;
                $code = 'STF';
                break;
            case '/ipcr/staff?page=3':
                $this->funct_id = 3;
                $code = 'SF';
                break;
            default:
                $this->funct_id = 0;
                break;
        };

        switch ($this->selected) {
            case 'sub_funct':
                auth()->user()->sub_functs()->attach(SubFunct::create([
                    'sub_funct' => $this->sub_funct,
                    'type' => 'ipcr',
                    'user_type' => 'staff',
                    'funct_id' => $this->funct_id,
                    'duration_id' => $this->duration->id,
                    'filter' => $this->filter
                ]));
                break;
            case 'output':
                if ($this->sub_funct_id) {
                    auth()->user()->outputs()->attach(Output::create([
                        'code' => $code,
                        'output' => $this->output,
                        'type' => 'ipcr',
                        'user_type' => 'staff',
                        'sub_funct_id' => $this->sub_funct_id,
                        'duration_id' => $this->duration->id,
                        'filter' => $this->filter
                    ]));
                    break;
                }
                auth()->user()->outputs()->attach(Output::create([
                    'code' => $code,
                    'output' => $this->output,
                    'type' => 'ipcr',
                    'user_type' => 'staff',
                    'funct_id' => $this->funct_id,
                    'duration_id' => $this->duration->id,
                    'filter' => $this->filter
                ]));
                break;
            case 'suboutput':
                auth()->user()->suboutputs()->attach(Suboutput::create([
                    'suboutput' => $this->suboutput,
                    'output_id' => $this->output_id,
                    'duration_id' => $this->duration->id
                ]));
                break;
            case 'target':                
                $subput = explode(',', $this->subput);

                if ($subput[0] == 'output') {
                    auth()->user()->targets()->attach(Target::create([
                        'target' => $this->target,
                        'output_id' => $subput[1],
                        'duration_id' => $this->duration->id,
                        'hasMultipleRating' => $this->hasMultipleRating,
                    ]));
                } elseif ($subput[0] == 'suboutput') {
                    auth()->user()->targets()->attach(Target::create([
                        'target' => $this->target,
                        'suboutput_id' => $subput[1],
                        'duration_id' => $this->duration->id,
                        'hasMultipleRating' => $this->hasMultipleRating,
                    ]));
                }
                break;
            case 'target_output':
                auth()->user()->targets()->syncWithoutDetaching([$this->target_id => ['target_output' => $this->target_output]]);
                break;
        }

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#435ebe",
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updateIpcr() {

        $this->validate();

        switch ($this->selected) {
            case 'sub_funct':
                SubFunct::where('id', $this->sub_funct_id)->update([
                    'sub_funct' => $this->sub_funct
                ]);
                break;
            case 'output':
                Output::where('id', $this->output_id)->update([
                    'output' => $this->output
                ]);
                break;
            case 'suboutput':
                Suboutput::where('id', $this->suboutput_id)->update([
                    'suboutput' => $this->suboutput
                ]);
                break;
            case 'target':  
                Target::where('id', $this->target_id)->update([
                    'target' => $this->target,
                    'hasMultipleRating' => $this->hasMultipleRating,
                ]);
                break;
            case 'target_output':
                auth()->user()->targets()->syncWithoutDetaching([$this->target_id => ['target_output' => $this->target_output]]);
                break;
        }

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Updated Successfully",
            'color' => "#28ab55",
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete() {
        switch ($this->selected) {
            case 'sub_funct':
                SubFunct::where('id',$this->sub_funct_id)->delete();
                break;
            case 'output':
                Output::where('id',$this->output_id)->delete();
                break;
            case 'suboutput':
                Suboutput::where('id',$this->suboutput_id)->delete();
                break;
            case 'target':  
                Target::where('id',$this->target_id)->delete();
                break;
            case 'target_output':
                auth()->user()->targets()->syncWithoutDetaching([$this->target_id => ['target_output' => null]]);
                break;
            case 'rating':
                Rating::where('id', $this->rating_id)->delete();
                break;
        }

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    /////////////////////////// SUBFUNCTION/OUTPUT/SUBOUTPUT/TARGET CONFIGURATION END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// PERCENTAGE CONFIGURATION ///////////////////////////

    public function percentage($category = null) {
        $this->selected = 'percent';

        if ($category) {
            $this->percent = $this->percentage;

            foreach (auth()->user()->sub_percentages()->where('type', 'ipcr')->where('user_type', 'staff')->get() as $sub_percentage) {
                $this->sub_percent[$sub_percentage->sub_funct_id] = $sub_percentage->value;
            }
        }
    }
    
    public function checkPercentage() {

        if (array_sum([$this->percent['core'], $this->percent['strategic'], $this->percent['support']]) != 100) {
            return false;
        }

        $funct = [
            'core' => false,
            'strategic' => false,
            'support' => false,
        ];

        foreach (auth()->user()->sub_functs()->where('type', 'ipcr')->where('user_type', 'staff')->get() as $sub_funct) {
            switch ($sub_funct->funct_id) {
                case 1:
                    $funct['core'] = true;
                    break;
                case 2:
                    $funct['strategic'] = true;
                    break;
                case 3:
                    $funct['support'] = true;
                    break;
            }
        }
        
        $total = count(array_filter($funct)) * 100;

        if ($total != array_sum($this->sub_percent)) {
            return false;
        }

        return true;
    }

    public function savePercentage() {
        
        $this->validate();

        if (!$this->checkPercentage()) {
            return $this->dispatchBrowserEvent('toastify', [
                'message' => "Percentage is not equal to 100",
                'color' => "#f3616d",
            ]);
        }

        Percentage::create([
            'core' => $this->percent['core'],
            'strategic' => $this->percent['strategic'],
            'support' => $this->percent['support'],
            'type' => 'ipcr',
            'user_type' => 'staff',
            'user_id' => auth()->user()->id,
            'duration_id' => $this->duration->id,
        ]);

        foreach (auth()->user()->sub_functs()->where('type', 'ipcr')->where('user_type', 'staff')->get() as $sub_funct) {
            SubPercentage::create([
                'value' => $this->sub_percent[$sub_funct->id],
                'sub_funct_id' => $sub_funct->id, 
                'type' => 'ipcr',
                'user_type' => 'staff',
                'user_id' => auth()->user()->id,
                'duration_id' => $this->duration->id,
            ]);
        }


        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#435ebe",
        ]);

        $this->mount();
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatePercentage() {
        
        $this->validate();

        if (!$this->checkPercentage()) {
            return $this->dispatchBrowserEvent('toastify', [
                'message' => "Percentage is not equal to 100",
                'color' => "#f3616d",
            ]);
        }

        Percentage::where('id', $this->percent['id'])->update([
            'core' => $this->percent['core'],
            'strategic' => $this->percent['strategic'],
            'support' => $this->percent['support'],
        ]);

        foreach (auth()->user()->sub_functs()->where('type', 'ipcr')->where('user_type', 'staff')->get() as $sub_funct) {
            $sub_percent = SubPercentage::where('sub_funct_id', $sub_funct->id)->where('user_id', auth()->user()->id)->update([
                'value' => $this->sub_percent[$sub_funct->id],
            ]);

            if (!$sub_percent) {
                SubPercentage::create([
                    'value' => $this->sub_percent[$sub_funct->id],
                    'sub_funct_id' => $sub_funct->id, 
                    'type' => 'ipcr',
                    'user_type' => 'staff',
                    'user_id' => auth()->user()->id,
                    'duration_id' => $this->duration->id,
                ]);
            }
        }


        $this->dispatchBrowserEvent('toastify', [
            'message' => "Updated Successfully",
            'color' => "#28ab55",
        ]);

        $this->mount();
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    /////////////////////////// PERCENTAGE CONFIGURATION END ///////////////////////////

    public function print() {
        $this->print = 'staff';
        
        $this->printInfos = PrintInfo::where('user_id', auth()->user()->id)
                        ->where('duration_id', $this->duration->id)
                        ->where('type', 'staff')
                        ->first();
        if($this->printInfos) {
            $this->printInfos->toArray();
        }
    }

    public function submitPrint() {
        $printInfo = PrintInfo::where('user_id', auth()->user()->id)
            ->where('duration_id', $this->duration->id)
            ->where('type', 'staff')
            ->first();

        if ($printInfo) {
            PrintInfo::where('id', $printInfo->id)->update([
                'position' => $this->printInfos['position'],
                'office' => $this->printInfos['office']
            ]);
        } else {
            PrintInfo::create([
                'position' => $this->printInfos['position'],
                'office' => $this->printInfos['office'],
                'type' => 'staff',
                'duration_id' => $this->duration->id,
                'user_id' => auth()->user()->id
            ]);
        }

        $this->dispatchBrowserEvent('close-modal');
    }

    public function file($id) {

        $this->selected = 'file';
        $this->rating_id = $id;
        $this->targetFiles = TargetFile::where('rating_id', $id)->get();
    }

    public function uploadFiles() {
        $this->validate();

        foreach ($this->files as $file) {
            $url = $file->store('document');

            TargetFile::create([
                'rating_id' => $this->rating_id,
                'file_new_name' => $url,
                'file_default_name' => $file->getClientOriginalName()
            ]);
        }
        
        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#28ab55",
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteFile($id) {
        $targetFile = TargetFile::find($id);
        TargetFile::where('id', $id)->delete();

        $this->targetFiles = TargetFile::where('rating_id', $targetFile->rating_id)->get();

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);
    }
    
    public function resetInput(){
        $this->percent = [];
        $this->sub_percent = [];    
        $this->funct_id = '';
        $this->sub_funct = '';
        $this->sub_funct_id = '';
        $this->output = '';
        $this->output_id = '';
        $this->suboutput = '';
        $this->subput = '';
        $this->target = '';
        $this->target_id = '';
        $this->target_output = '';    
        $this->review_id = '';
        $this->approve_id = '';
        $this->output_finished = '';
        $this->efficiency = '';
        $this->quality = '';
        $this->timeliness = '';
        $this->accomplishment = '';

        $this->printInfos = [];

        $this->files = null;
        $this->itteration++;
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
