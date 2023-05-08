<?php

namespace App\Http\Livewire;

use App\Models\Pmt;
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
use App\Models\PrintInfo;
use App\Models\Suboutput;
use App\Models\Percentage;
use Livewire\WithPagination;
use App\Models\SubPercentage;
use App\Notifications\ApprovalNotification;

class OpcrLivewire extends Component
{
    use WithPagination;
    
    public $required = false;

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

    public $sub_percentages;

    public $selected = 'output';
    public $approval;
    public $approvalStandard;
    public $assess;
    public $review_user = [];
    public $approve_user;

    public $target_id;
    public $target_output;
    public $alloted_budget;
    public $responsible;

    public $review_id = [];
    public $approve_id;
    public $highestOffice;
    
    public $rating_id;
    public $selectedTarget;
    public $output_finished;
    public $accomplishment;
    public $efficiency;
    public $quality;
    public $timeliness;

    public $targetOutput;

    public $add = false;
    public $targetsSelected = [];

    public $filter = '';

    public $hasTargetOutput = false;
    public $hasRating = false;

    public $selectedOutput;
    public $allocatedTargetSelected = [];
    public $targetAllocated;

    public $print;

    public $printInfos = [];

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

        'output_finished' => ['nullable', 'required_if:selected,rating', 'numeric'],
        'accomplishment' => ['required_if:selected,rating'],
        
        'target_output' => ['nullable', 'required_if:selected,target_output', 'numeric'],
        'alloted_budget' => ['nullable', 'required_if:selected,target_output', 'numeric'],
        'responsible' => ['nullable', 'required_if:selected,target_output'],
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

        'output_finished.numeric' => 'Output Finished should be a number.',
        'output_finished.required_if' => 'Output Finished cannot be null.',
        'accomplishment.required_if' => 'Actual Accomplishment cannot be null.',
        
        'target_output.required_if' => 'Target Output cannot be null',
        'target_output.numeric' => 'Target Output should be a number.',
        'alloted_budget.required_if' => 'Alloted Budget cannot be null',
        'alloted_budget.numeric' => 'Alloted Budget should be a number.',
        'responsible.required_if' => 'Responsible Person/Office cannot be null',
    ];
    
    public function updated($property)
    {
        $this->validateOnly($property);
    }


    public function mount() {
        $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', auth()->user()->id)->where('duration_id', $this->duration->id)->first();
            $this->sub_percentages = SubPercentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', auth()->user()->id)->where('duration_id', $this->duration->id)->get();

            $this->approval = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'opcr')->where('duration_id', $this->duration->id)->where('user_type', 'office')->first();
            $this->approvalStandard = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'standard')->where('duration_id', $this->duration->id)->where('user_type', 'office')->where('approve_status', 1)->first();
            $this->assess = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'opcr')->where('duration_id', $this->duration->id)->where('user_type', 'office')->first();
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

        foreach(auth()->user()->targets as $target) {
            $this->targetsSelected[$target->id] = $target->id;
        }
    }

    public function render()
    {
        if ($this->add) {
            return view('components.opcr-add', [
                'functs' => Funct::all(),
                'filter' => $this->filter,
            ]);
        } else {
            foreach (auth()->user()->targets as $target) {
                if (($target->suboutput_id && $target->suboutput->output->user_type == 'office') || ($target->output_id && $target->output->user_type == 'office')) {
                    if (!isset($target->pivot->target_output)) {
                        $this->hasTargetOutput = false;
                        break;
                    } else {
                        $this->hasTargetOutput = true;
                    }
                }
            }

            foreach (auth()->user()->targets as $target) {
                if (($target->suboutput_id && $target->suboutput->output->user_type == 'office') || ($target->output_id && $target->output->user_type == 'office')) {
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

            return view('livewire.opcr-livewire', [
                'functs' => Funct::paginate(1)
            ]);
        }
    }

    /////////////////////////// RATING OF OPCR ///////////////////////////

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

    /////////////////////////// RATING OF OPCR END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// SUBMITION OF OPCR ///////////////////////////

    public function submit($type) {

        $this->selected = 'submition';

        $depths;

        foreach(Office::all() as $office) {
            $depths[$office->id] = $office->getDepthAttribute();
        }

        foreach ($depths as $id => $depth) {
            if ($depth == 0) {
                $office = Office::find($id);
                break;
            }
        }

        $this->approve_id = $office->users()->where('isHead', 1)->pluck('id')->first();
        
        if (!$this->approve_id) {
            return $this->dispatchBrowserEvent('toastify', [
                'message' => "No Head Found!",
                'color' => "#f3616d",
            ]);
        }

        $pmt_head = Pmt::where('isHead', 1)->first();

        if (!$pmt_head) {
            return $this->dispatchBrowserEvent('toastify', [
                'message' => "No Head Found!",
                'color' => "#f3616d",
            ]);
        }

        foreach (Pmt::all() as $indi_pmt) {
            if (isset($indi_pmt->user)) {
                $this->review_id[$indi_pmt->user->id] = $indi_pmt->user->id;
            } 
        }

        $approval = Approval::create([
            'name' => $type,
            'user_id' => auth()->user()->id,
            'approve_id' => $this->approve_id,
            'type' => 'opcr',
            'user_type' => 'office',
            'duration_id' => $this->duration->id
        ]);

        
        $approve = $approval;

        if (isset($this->review_id[auth()->user()->id])) {
            unset($this->review_id[auth()->user()->id]);
        }
        
        $approve->reviewers()->attach($this->review_id);
        
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

    /////////////////////////// SUBMITION OF OPCR END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// SUBFUNCTION/OUTPUT/SUBOUTPUT/TARGET CONFIGURATION ///////////////////////////

    public function selectOpcr($type, $output, $id, $category = null) {
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
                $this->selectedOutput = $output;
                $this->allocatedTargetSelected[$id] = $id;
                if ($category) {
                    $data = auth()->user()->targets()->where('id', $id)->first();

                    $this->target_output = $data->pivot->target_output;
                    $this->alloted_budget = $data->pivot->alloted_budget;
                    $this->responsible = $data->pivot->responsible;
                    $this->targetAllocated = $data->pivot->target_allocated;
                }

                if (isset($this->targetAllocated)) {
                    foreach (auth()->user()->targets()->wherePivot('target_allocated', $id)->get() as $target) {
                        $this->allocatedTargetSelected[$target->id] = $target->id;
                    }
                }
                break; 
        }
    }

    public function saveOpcr() {
        $this->validate();

        switch (str_replace(url('/'), '', url()->previous())) {
            case '/opcr':
                $this->funct_id = 1;
                $code = 'CF';
                break;
            case '/opcr?page=2':
                $this->funct_id = 2;
                $code = 'STF';
                break;
            case '/opcr?page=3':
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
                    'type' => 'opcr',
                    'user_type' => 'office',
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
                        'type' => 'opcr',
                        'user_type' => 'office',
                        'sub_funct_id' => $this->sub_funct_id,
                        'duration_id' => $this->duration->id,
                        'filter' => $this->filter
                    ]));
                    break;
                }
                auth()->user()->outputs()->attach(Output::create([
                    'code' => $code,
                    'output' => $this->output,
                    'type' => 'opcr',
                    'user_type' => 'office',
                    'funct_id' => $this->funct_id,
                    'duration_id' => $this->duration->id,
                    'filter' => $this->filter
                ]));
                break;
            case 'suboutput':
                auth()->user()->suboutputs()->attach(Suboutput::create([
                    'suboutput' => $this->suboutput,
                    'output_id' => $this->output_id,
                    'duration_id' => $this->duration->id,
                    'added_by' => auth()->user()->id
                ]));
                break;
            case 'target':                
                $subput = explode(',', $this->subput);

                if ($subput[0] == 'output') {
                    auth()->user()->targets()->attach(Target::create([
                        'target' => $this->target,
                        'output_id' => $subput[1],
                        'required' => $this->required,
                        'duration_id' => $this->duration->id,
                        'added_by' => auth()->user()->id
                    ]));
                } elseif ($subput[0] == 'suboutput') {
                    auth()->user()->targets()->attach(Target::create([
                        'target' => $this->target,
                        'suboutput_id' => $subput[1],
                        'required' => $this->required,
                        'duration_id' => $this->duration->id,
                        'added_by' => auth()->user()->id
                    ]));
                }
                break;
            case 'target_output':
                if (auth()->user()->targets()->where('id', $this->target_id)->first()->pivot->target_allocated == null) {
                    foreach ($this->allocatedTargetSelected as $id => $value) {
                        auth()->user()->targets()->syncWithoutDetaching([$id => ['alloted_budget' => $this->alloted_budget, 'target_allocated' => $this->target_id]]);
                    }
                }
                auth()->user()->targets()->syncWithoutDetaching([$this->target_id => ['target_output' => $this->target_output, 'alloted_budget' => $this->alloted_budget, 'responsible' => $this->responsible]]);
                break;
        }

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#435ebe",
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updateOpcr() {

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
                foreach ($this->allocatedTargetSelected as $id => $value) {
                    if ($value == false) {
                        auth()->user()->targets()->syncWithoutDetaching([$id => ['alloted_budget' => null, 'target_allocated' => null]]);
                    } else {
                        auth()->user()->targets()->syncWithoutDetaching([$id => ['alloted_budget' => $this->alloted_budget, 'target_allocated' => $this->target_id]]);
                    }
                }
                auth()->user()->targets()->syncWithoutDetaching([$this->target_id => ['target_output' => $this->target_output, 'alloted_budget' => $this->alloted_budget, 'responsible' => $this->responsible]]);
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
                foreach ($this->allocatedTargetSelected as $id => $value) {
                    auth()->user()->targets()->syncWithoutDetaching([$id => ['alloted_budget' => null, 'target_allocated' => null]]);
                }
                auth()->user()->targets()->syncWithoutDetaching([$this->target_id => ['target_output' => null, 'alloted_budget' => null, 'responsible' => null]]);
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

            foreach (auth()->user()->sub_percentages()->where('type', 'opcr')->where('user_type', 'office')->get() as $sub_percentage) {
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

        foreach (auth()->user()->sub_functs()->where('type', 'opcr')->where('user_type', 'office')->get() as $sub_funct) {
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
            'type' => 'opcr',
            'user_type' => 'office',
            'user_id' => auth()->user()->id,
            'duration_id' => $this->duration->id,
        ]);

        foreach (auth()->user()->sub_functs()->where('type', 'opcr')->where('user_type', 'office')->get() as $sub_funct) {
            SubPercentage::create([
                'value' => $this->sub_percent[$sub_funct->id],
                'sub_funct_id' => $sub_funct->id, 
                'type' => 'opcr',
                'user_type' => 'office',
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

        foreach (auth()->user()->sub_functs()->where('type', 'opcr')->where('user_type', 'office')->get() as $sub_funct) {
            $sub_percent = SubPercentage::where('sub_funct_id', $sub_funct->id)->where('user_id', null)->update([
                'value' => $this->sub_percent[$sub_funct->id],
            ]);

            if (!$sub_percent) {
                SubPercentage::create([
                    'value' => $this->sub_percent[$sub_funct->id],
                    'sub_funct_id' => $sub_funct->id, 
                    'type' => 'opcr',
                    'user_type' => 'office',
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
        $this->print = 'office';
        
        $this->printInfos = PrintInfo::where('user_id', auth()->user()->id)
                    ->where('duration_id', $this->duration->id)
                    ->where('type', 'office')
                    ->first();
                    
        if($this->printInfos) {
            $this->printInfos->toArray();
        }
    }
    
    public function submitPrint() {
        $printInfo = PrintInfo::where('user_id', auth()->user()->id)
            ->where('duration_id', $this->duration->id)
            ->where('type', 'office')
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
                'type' => 'office',
                'duration_id' => $this->duration->id,
                'user_id' => auth()->user()->id
            ]);
        }

        $this->dispatchBrowserEvent('close-modal');
    }

    public function add() {
        $this->add = true;
    }
    public function getOpcr() {
        $this->add = false;
        $target_ids = [];
        $suboutput_ids = [];
        $output_ids = [];
        $sub_funct_ids = [];

        foreach ($this->targetsSelected as $id) {            
            if ($target = Target::where('id',$id)->first()) {
                array_push($target_ids, $id);
                if ($target->output) {
                    array_push($output_ids, $target->output_id);
                    if ($target->output->sub_funct) {
                        array_push($sub_funct_ids, $target->output->sub_funct_id);
                    }
                } else if ($target->suboutput) {
                    array_push($suboutput_ids, $target->suboutput_id);
                    if ($target->suboutput->output) {
                        array_push($output_ids, $target->suboutput->output_id);
                        if ($target->suboutput->output->sub_funct) {
                            array_push($sub_funct_ids, $target->suboutput->output->sub_funct_id);
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
        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#435ebe",
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
        $this->review_id = [];
        $this->approve_id = '';
        $this->output_finished = '';
        $this->efficiency = '';
        $this->quality = '';
        $this->timeliness = '';
        $this->accomplishment = '';
        $this->allocatedTargetSelected = [];

        $this->target_id = '';
        $this->target_output = '';
        $this->alloted_budget = '';
        $this->responsible = '';
        
        $this->printInfos = [];
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
