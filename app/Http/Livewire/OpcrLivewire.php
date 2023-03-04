<?php

namespace App\Http\Livewire;

use App\Models\Pmt;
use App\Models\User;
use App\Models\Funct;
use App\Models\Office;
use App\Models\Rating;
use App\Models\Target;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\Percentage;
use Livewire\WithPagination;
use App\Models\SubPercentage;
use App\Notifications\ApprovalNotification;

class OpcrLivewire extends Component
{
    use WithPagination;

    public $selected = 'output';
    public $approval;
    public $approvalStandard;
    public $assess;
    public $review_user;
    public $approve_user;

    public $target_id;
    public $target_output;
    public $alloted_budget;
    public $responsible;

    public $review_id;
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

    public $dummy;

    public $add = false;
    public $targetsSelected = [];

    protected $listeners = ['percentage', 'resetIntput'];

    protected $rules = [
        'output_finished' => ['nullable', 'required_if:selected,rating', 'numeric'],
        'efficiency' => ['required_without_all:quality,timeliness,dummy'],
        'quality' => ['required_without_all:efficiency,timeliness,dummy'],
        'timeliness' => ['required_without_all:efficiency,quality,dummy'],
        'accomplishment' => ['required_if:selected,rating'],
        
        'target_output' => ['nullable', 'required_if:selected,target_output', 'numeric'],
        'alloted_budget' => ['nullable', 'required_if:selected,target_output', 'numeric'],
        'responsible' => ['nullable', 'required_if:selected,target_output'],
    ];

    protected $messages = [
        'output_finished.numeric' => 'Output Finished should be a number.',
        'output_finished.required_if' => 'Output Finished cannot be null.',
        'efficiency.required_without_all' => 'Efficiency cannot be null.',
        'quality.required_without_all' => 'Quality cannot be null.',
        'timeliness.required_without_all' => 'Timeliness cannot be null.',
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
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->where('duration_id', $this->duration->id)->first();
            $this->sub_percentages = SubPercentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->where('duration_id', $this->duration->id)->get();

            $this->approval = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'opcr')->where('duration_id', $this->duration->id)->where('user_type', 'office')->first();
            $this->approvalStandard = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'standard')->where('duration_id', $this->duration->id)->where('user_type', 'office')->where('approve_status', 1)->first();
            $this->assess = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'opcr')->where('duration_id', $this->duration->id)->where('user_type', 'office')->first();
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


            foreach(auth()->user()->targets()->where('duration_id', $this->duration->id)->get() as $target) {
                $this->targetsSelected[$target->id] = $target->id;
            }


        }

        $this->dummy = 'has data';
    }

    public function render()
    {
        if ($this->add) {
            return view('components.opcr-add', [
                'functs' => Funct::all()
            ]);
        } else {
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
        $this->dummy = '';
    }

    public function editRating($rating_id){
        $this->selected = 'rating';
        $this->rating_id = $rating_id;

        $rating = auth()->user()->ratings()->where('id', $rating_id)->first();

        $this->selectedTarget = auth()->user()->targets()->where('id', $rating->target_id)->first();
        $this->targetOutput = $this->selectedTarget->pivot->target_output;
        
        $this->output_finished = $rating->output_finished;
        $this->accomplishment = $rating->accomplishment;
        $this->efficiency = $rating->efficiency;
        $this->quality = $rating->quality;
        $this->timeliness = $rating->timeliness;
    }

    public function saveRating($category){

        $this->validate();

        if ($category == 'add') {
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

            Rating::create([
                'output_finished' => $this->output_finished,
                'accomplishment' => $this->accomplishment,
                'efficiency' => $this->efficiency,
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

        $pmt = Pmt::where('isHead', 1)->first();

        if (!$pmt) {
            return $this->dispatchBrowserEvent('toastify', [
                'message' => "No Head Found!",
                'color' => "#f3616d",
            ]);
        }

        $this->review_id = $pmt->user->id;

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
            'type' => 'opcr',
            'user_type' => 'office',
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

    /////////////////////////// SUBMITION OF OPCR END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// SUBFUNCTION/OUTPUT/SUBOUTPUT/TARGET CONFIGURATION ///////////////////////////

    public function selectOpcr($type, $id, $category = null) {
        $this->selected = $type;
        switch($type) {
            case 'target_output':
                $this->target_id = $id;
                if ($category) {
                    $data = auth()->user()->targets()->where('id', $id)->first();

                    $this->target_output = $data->pivot->target_output;
                    $this->alloted_budget = $data->pivot->alloted_budget;
                    $this->responsible = $data->pivot->responsible;
                }
                break; 
        }
    }

    public function saveOpcr() {

        $this->validate();

        switch ($this->selected) {
            case 'target_output':
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
            case 'target_output':
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

    /////////////////////////// SUBFUNCTION/OUTPUT/SUBOUTPUT/TARGET CONFIGURATION END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// PERCENTAGE CONFIGURATION ///////////////////////////

    

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
