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
use App\Models\Suboutput;
use App\Models\Percentage;
use Livewire\WithPagination;
use App\Models\SubPercentage;
use App\Notifications\ApprovalNotification;

class FacultyLivewire extends Component
{
    use WithPagination;

    public $funct_id;
    public $sub_funct;
    public $sub_funct_id;
    public $output;
    public $output_id;
    public $suboutput;
    public $subput;
    public $target;

    public $selected = 'output';
    public $approval;
    public $approvalStandard;
    public $assess;
    public $review_user;
    public $approve_user;

    public $target_id;
    public $target_output;

    public $review_id;
    public $approve_id;
    public $highestOffice = [];
    
    public $rating_id;
    public $selectedTarget;
    public $output_finished;
    public $efficiency;
    public $quality;
    public $timeliness;
    public $accomplishment;

    public $targetOutput;

    public $add = false;
    public $targetsSelected = [];

    public $filter = "";

    public $hasTargetOutput = false;
    public $hasRating = false;

    protected $listeners = ['percentage', 'resetIntput'];

    protected $rules = [
        'output_finished' => ['nullable', 'required_if:selected,rating', 'numeric'],
        'accomplishment' => ['required_if:selected,rating'],
        
        'target_output' => ['nullable', 'required_if:selected,target_output', 'numeric'],

        'sub_funct' => ['required_if:selected,sub_funct'],
        'output' => ['required_if:selected,output'],
        'output_id' => ['nullable', 'required_if:selected,output_id'],
        'suboutput' => ['required_if:selected,suboutput'],
        'subput' => ['nullable', 'required_if:selected,target_id'],
        'target' => ['required_if:selected,target'],
    ];

    protected $messages = [
        'output_finished.required_if' => 'Output Finished cannot be null.',
        'output_finished.numeric' => 'Output Finished should be a number.',
        'accomplishment' => 'Actual Accomplishment cannot be null.',
        
        'target_output.required_if' => 'Target Output cannot be null',
        'target_output.numeric' => 'Target Output should be a number.',

        'sub_funct.required_if' => 'Sub Function cannot be null',
        'output.required_if' => 'Output cannot be null',
        'output_id.required_if' => 'Output cannot be null',
        'suboutput.required_if' => 'Suboutput cannot be null',
        'subput.required_if' => 'Suboutput/Output cannot be null',
        'target.required_if' => 'Target cannot be null',
    ];
    
    public function updated($property)
    {
        $this->validateOnly($property);
    }


    public function mount() {
        $notDesignated = false;
        foreach (auth()->user()->account_types as $account_type){
            if (str_contains(strtolower($account_type->account_type), 'no')){
                $notDesignated = true;
                break;
            }
        }

        $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'ipcr')->where('user_type', 'faculty')->where('user_id', null)->where('duration_id', $this->duration->id)->first();

            $this->approval = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'faculty')->first();
            
            if ($notDesignated) {
                $this->approvalStandard = true;
            } else{
                $this->approvalStandard = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'standard')->where('duration_id', $this->duration->id)->where('user_type', 'faculty')->where('approve_status', 1)->first();
            }
            
            $this->assess = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'faculty')->first();
            if ($this->assess) {
                foreach ($this->assess->reviewers as $reviewer) {
                    if ($reviewer->pivot->review_message) {
                        $this->review_user['name'] = $reviewer->name;
                        $this->review_user['message'] = $reviewer->pivot->review_message;
                    }
                }

                $this->approve_user['name'] = User::where('id', $this->assess->approve_id)->pluck('name')->first();
                $this->approve_user['message'] = $this->assess->approve_message;
            } elseif ($this->approval) {
                foreach ($this->approval->reviewers as $reviewer) {
                    if ($reviewer->pivot->review_message) {
                        $this->review_user['name'] = $reviewer->name;
                        $this->review_user['message'] = $reviewer->pivot->review_message;
                    }
                }

                $this->approve_user['name'] = User::where('id', $this->approval->approve_id)->pluck('name')->first();
                $this->approve_user['message'] = $this->approval->approve_message;
            }

            foreach(Target::where('required', true)->get() as $target) {
                $this->targetsSelected[$target->id] = $target->id;
            }
            foreach(auth()->user()->targets()->where('duration_id', $this->duration->id)->get() as $target) {
                $this->targetsSelected[$target->id] = $target->id;
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
        if ($this->add) {
            return view('components.faculty-add', [
                'functs' => Funct::all()
            ]);
        } else {
            foreach (auth()->user()->targets as $target) {
                if (($target->suboutput_id && $target->suboutput->output->user_type == 'faculty') || ($target->output_id && $target->output->user_type == 'faculty')) {
                    if (!isset($target->pivot->target_output)) {
                        $this->hasTargetOutput = false;
                        break;
                    } else {
                        $this->hasTargetOutput = true;
                    }
                }
            }

            foreach (auth()->user()->targets as $target) {
                if (($target->suboutput_id && $target->suboutput->output->user_type == 'faculty') || ($target->output_id && $target->output->user_type == 'faculty')) {
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

            return view('livewire.faculty-livewire', [
                'functs' => Funct::paginate(1)
            ]);
        }
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
    
                $output_pecentage = $this->output_finished/$this->targetOutput * 100;
                
                if ($output_pecentage >= $eff_5) {
                    $efficiency = 5;
                } elseif ($output_pecentage >= $eff_4) {
                    $efficiency = 4;
                } elseif ($output_pecentage >= $eff_3) {
                    $efficiency = 3;
                } elseif ($output_pecentage >= $eff_2) {
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

            if(!$this->efficiency){
                $divisor++;
            }
            if(!$this->quality){
                $divisor++;
            }
            if(!$this->timeliness){
                $divisor++;
            }
            $number = ((int)$this->efficiency + (int)$this->quality + (int)$this->timeliness) / (3 - $divisor);
            $average = number_format((float)$number, 2, '.', '');
            
            $standard = $this->selectedTarget->standards()->first();

            if ($this->efficiency == '') {
                if ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1){
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        'efficiency' => ['Efficiency cannot be null.'],
                     ]);
                     throw $error;
                } else {
                    $this->efficiency = null;
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

            Rating::where('id', $this->rating_id)->update([
                'output_finished' => $this->output_finished,
                'accomplishment' => $this->accomplishment,
                'efficiency' => $this->efficiency,
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

        $review_ids = [];

        foreach (auth()->user()->offices()->pluck('id')->toArray() as $id) {
            $office = Office::find($id);

            if (auth()->user()->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                if ($office->users()->wherePivot('isHead', 1)->pluck('id')->first()) {
                    array_push($review_ids, $office->users()->wherePivot('isHead', 1)->pluck('id')->first());
                }
            } elseif (auth()->user()->offices()->where('id', $id)->first()->pivot->isHead) {
                $parent_office = Office::where('id', $office->parent_id)->first();
                if ($parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first()) {
                    array_push($review_ids, $parent_office->users()->wherePivot('isHead', 1)->pluck('id')->first());
                }
            }
        }

        if (count($this->highestOffice) > 1) {
            $numberOfTarget = [];
            $x = 0;
            foreach (auth()->user()->sub_functs()->where('user_type', 'faculty')->where('duration_id', $this->duration->id)->where('funct_id', 1)->get() as $sub_funct) {
                $targets = 0;
                foreach (auth()->user()->outputs()->where('sub_funct_id', $sub_funct->id)->get() as $output) {
                    foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target) {
                        $targets++;
                    }
                }
                $numberOfTarget[$x] = $targets;
                $x++;
            }

            if ((isset($numberOfTarget[0]) && !isset($numberOfTarget[1])) || ($numberOfTarget[0] > $numberOfTarget[1])) {
                foreach ($this->highestOffice as $id => $value) {

                    $office = Office::find($id);

                    if (str_contains(strtolower($office->parent->office_name), 'academic')) {
                        if (auth()->user()->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                            
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
                foreach ($this->highestOffice as $id => $value) {

                    $office = Office::find($id);

                    if (!str_contains(strtolower($office->parent->office_name), 'academic')) {
                        if (auth()->user()->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                            
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
            foreach ($this->highestOffice as $id => $value) {

                $office = Office::find($id);
    
                if (auth()->user()->offices()->where('id', $id)->first()->pivot->isHead == 0) {
                    
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
            'name' => $type,
            'user_id' => auth()->user()->id,
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
                $reviewer->notify(new ApprovalNotification($approval, auth()->user(), 'Submitting'));
            }
        } else {
            $reviewer = User::where('id', $review_ids[0])->first();
            $reviewer->notify(new ApprovalNotification($approval, auth()->user(), 'Submitting'));
        }
        
        $approver = User::where('id', $this->approve_id)->first();
        $approver->notify(new ApprovalNotification($approval, auth()->user(), 'Submitting'));

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Submitted Successfully",
            'color' => "#435ebe",
        ]);

        $this->mount();
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
                    $this->required = $data->required;
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
            case '/ipcr/faculty':
                $this->funct_id = 1;
                $code = 'CF';
                break;
            case '/ipcr/faculty?page=2':
                $this->funct_id = 2;
                $code = 'STF';
                break;
            case '/ipcr/faculty?page=3':
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
                    'user_type' => 'faculty',
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
                        'user_type' => 'faculty',
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
                    'user_type' => 'faculty',
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
                    $target = Target::create([
                        'target' => $this->target,
                        'output_id' => $subput[1],
                        'duration_id' => $this->duration->id
                    ]);
                    auth()->user()->targets()->attach($target->id);
                } elseif ($subput[0] == 'suboutput') {
                    $target = Target::create([
                        'target' => $this->target,
                        'suboutput_id' => $subput[1],
                        'duration_id' => $this->duration->id
                    ]);
                    auth()->user()->targets()->attach($target->id);
                }
                $this->targetsSelected[$target->id] = $target->id;
                break;
            case 'target_output':
                auth()->user()->targets()->syncWithoutDetaching([$this->target_id => ['target_output' => $this->target_output]]);
                break;
        }
        $this->updateSubPercentage();

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
                    'required' => $this->required
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
        $this->updateSubPercentage();

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function add() {
        $this->add = true;
    }

    public function getIpcr() {
        $this->add = false;
        $target_ids = [];
        $suboutput_ids = [];
        $output_ids = [];
        $sub_funct_ids = [];
        $core_sub_funct = [];

        $selected_targets = 0;

        foreach ($this->targetsSelected as $id) {            
            if ($target = Target::where('id',$id)->first()) {
                array_push($target_ids, $id);
                if ($target->output) {
                    array_push($output_ids, $target->output_id);
                    if ($target->output->sub_funct) {
                        array_push($sub_funct_ids, $target->output->sub_funct_id);
                        if ($target->output->sub_funct->funct_id == 1) {
                            array_push($core_sub_funct, $target->output->sub_funct_id);
                        }
                    }
                } else if ($target->suboutput) {
                    array_push($suboutput_ids, $target->suboutput_id);
                    if ($target->suboutput->output) {
                        array_push($output_ids, $target->suboutput->output_id);
                        if ($target->suboutput->output->sub_funct) {
                            array_push($sub_funct_ids, $target->suboutput->output->sub_funct_id);
                            if ($target->output->sub_funct->funct_id == 1) {
                                array_push($core_sub_funct, $target->output->sub_funct_id);
                            }
                        }
                    }
                }
            }
        }

        if ($target_ids) {
            auth()->user()->targets()->sync($target_ids);
        } else {
            auth()->user()->targets()->detach();
        }
        if ($suboutput_ids) {
            auth()->user()->suboutputs()->sync($suboutput_ids);
        } else {
            auth()->user()->suboutputs()->detach();
        }
        if ($output_ids) {
            auth()->user()->outputs()->sync($output_ids);
        } else {
            auth()->user()->outputs()->detach();
        }
        if ($sub_funct_ids) {
            auth()->user()->sub_functs()->sync($sub_funct_ids);
        } else {
            auth()->user()->sub_functs()->detach();
        }

        $this->updateSubPercentage();

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#435ebe",
        ]);
        $this->mount();
    }  

    /////////////////////////// SUBFUNCTION/OUTPUT/SUBOUTPUT/TARGET CONFIGURATION END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// PERCENTAGE CONFIGURATION ///////////////////////////

    public function updateSubPercentage() {
        $targets = 0;
        $core_sub_funct = auth()->user()->sub_functs()->where('funct_id', 1)->pluck('id')->toArray();
        
        foreach (auth()->user()->targets()->pluck('id')->toArray() as $id) {            
            if ($target = Target::where('id',$id)->first()) {
                if ($target->output) {
                    if ($target->output->sub_funct_id == end($core_sub_funct)) {
                        $targets++;
                    }
                } else if ($target->suboutput) {
                    if ($target->suboutput->output) {
                        if ($target->suboutput->output->sub_funct_id == end($core_sub_funct)) {
                            $targets++;
                        }
                    }
                }
            }
        }

        $sub_percent1 = ($targets/18)*100;
        $sub_percent2 = 100 - $sub_percent1;


        if (count($core_sub_funct) > 1) {
            $first = true;
            foreach (array_unique($core_sub_funct) as $id) {
                if ($first) {
                    $sub_percent = SubPercentage::where('sub_funct_id', $id)->where('user_id', auth()->user()->id)->update([
                        'value' => $sub_percent2,
                    ]);
        
                    if (!$sub_percent) {
                        SubPercentage::create([
                            'value' => $sub_percent2,
                            'sub_funct_id' => $id, 
                            'type' => 'ipcr',
                            'user_type' => 'faculty',
                            'user_id' => auth()->user()->id,
                            'duration_id' => $this->duration->id,
                        ]);
                    }
                    $first = false;
                } else {
                    $sub_percent = SubPercentage::where('sub_funct_id', $id)->where('user_id', auth()->user()->id)->update([
                        'value' => $sub_percent1,
                    ]);
        
                    if (!$sub_percent) {
                        SubPercentage::create([
                            'value' => $sub_percent1,
                            'sub_funct_id' => $id, 
                            'type' => 'ipcr',
                            'user_type' => 'faculty',
                            'user_id' => auth()->user()->id,
                            'duration_id' => $this->duration->id,
                        ]);
                    }
                }
            }
        } else {
            foreach (array_unique($core_sub_funct) as $id) {
                $sub_percent = SubPercentage::where('sub_funct_id', $id)->where('user_id', auth()->user()->id)->update([
                    'value' => 100,
                ]);

                if (!$sub_percent) {
                    SubPercentage::create([
                        'value' => 100,
                        'sub_funct_id' => $id, 
                        'type' => 'ipcr',
                        'user_type' => 'faculty',
                        'user_id' => auth()->user()->id,
                        'duration_id' => $this->duration->id,
                    ]);
                }
            }
        }
    }

    /////////////////////////// PERCENTAGE CONFIGURATION END ///////////////////////////

    
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
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
