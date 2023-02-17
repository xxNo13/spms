<?php

namespace App\Http\Livewire;

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

class FacultyLivewire extends Component
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

    public $review_id;
    public $approve_id;
    public $highestOffice = [];
    
    public $rating_id;
    public $selectedTarget;
    public $output_finished;
    public $efficiency;
    public $quality;
    public $timeliness;

    public $targetOutput;

    public $dummy;

    public $add = false;
    public $targetsSelected = [];

    protected $listeners = ['percentage', 'resetIntput'];

    protected $rules = [
        'output_finished' => ['required_if:selected,rating'],
        'efficiency' => ['required_without_all:quality,timeliness,dummy'],
        'quality' => ['required_without_all:efficiency,timeliness,dummy'],
        'timeliness' => ['required_without_all:efficiency,quality,dummy'],
        
        'target_output' => ['nullable', 'required_if:selected,target_output', 'numeric'],
    ];

    protected $messages = [
        'output_finished.required_if' => 'Output Finished cannot be null.',
        'efficiency.required_without_all' => 'Efficiency cannot be null.',
        'quality.required_without_all' => 'Quality cannot be null.',
        'timeliness.required_without_all' => 'Timeliness cannot be null.',
        
        'target_output.required_if' => 'Target Output cannot be null',
        'target_output.numeric' => 'Target Output should be a number.',
    ];
    
    public function updated($property)
    {
        $this->validateOnly($property);
    }


    public function mount() {
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'ipcr')->where('user_type', 'faculty')->where('user_id', null)->where('duration_id', $this->duration->id)->first();

            $this->approval = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'faculty')->first();
            $this->approvalStandard = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'standard')->where('duration_id', $this->duration->id)->where('user_type', 'faculty')->first();
            $this->assess = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'faculty')->first();
            if ($this->assess) {
                $this->review_user['name'] = User::where('id', $this->assess->review_id)->pluck('name')->first();
                $this->review_user['message'] = $this->assess->review_message;

                $this->approve_user['name'] = User::where('id', $this->assess->approve_id)->pluck('name')->first();
                $this->approve_user['message'] = $this->assess->approve_message;
            } elseif ($this->approval) {
                $this->review_user['name'] = User::where('id', $this->approval->review_id)->pluck('name')->first();
                $this->review_user['message'] = $this->approval->review_message;

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

        $this->dummy = 'has data';
    }

    public function render()
    {
        if ($this->add) {
            return view('components.faculty-add', [
                'functs' => Funct::all()
            ]);
        } else {
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
        $this->dummy = '';
    }

    public function editRating($rating_id){
        $this->selected = 'rating';
        $this->rating_id = $rating_id;

        $rating = auth()->user()->ratings()->where('id', $rating_id)->first();

        $this->selectedTarget = auth()->user()->targets()->where('id', $rating->target_id)->first();
        $this->targetOutput = $this->selectedTarget->pivot->target_output;
        
        $this->output_finished = strtok($rating->accomplishment, "/");
        $this->efficiency = $rating->efficiency;
        $this->quality = $rating->quality;
        $this->timeliness = $rating->timeliness;
    }

    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public function saveRating($category){

        $this->validate();

        if ($category == 'add') {
            $divisor = 0;
            $qua = "";
            $time = "";
            $accomplishment = $this->output_finished . "/" . $this->targetOutput;
            $standard = $this->selectedTarget->standards()->first();
            
            switch($this->quality) {
                case "5":
                        if (str_contains($standard->qua_5, "with")) {
                            $qua = $standard->qua_5;
                        } else {
                            $qua = "with " .  $standard->qua_5;
                        }
                    break;
                case "4":
                        if (str_contains($standard->qua_4, "with")) {
                            $qua = $standard->qua_4;
                        } else {
                            $qua = "with " .  $standard->qua_4;
                        }
                    break;
                case "3":
                        if (str_contains($standard->qua_3, "with")) {
                            $qua = $standard->qua_3;
                        } else {
                            $qua = "with " .  $standard->qua_3;
                        }
                    break;
                case "2":
                        if (str_contains($standard->qua_2, "with")) {
                            $qua = $standard->qua_2;
                        } else {
                            $qua = "with " .  $standard->qua_2;
                        }
                    break;
                case "1":
                        if (str_contains($standard->qua_1, "with")) {
                            $qua = $standard->qua_1;
                        } else {
                            $qua = "with " .  $standard->qua_1;
                        }
                    break;
            }

            switch ($this->timeliness) {
                case "5":
                    $time = "submitted " . $standard->time_5;
                    break;
                case "4":
                    $time = "submitted " . $standard->time_4;
                    break;
                case "3":
                    $time = "submitted " . $standard->time_3;
                    break;
                case "2":
                    $time = "submitted " . $standard->time_2;
                    break;
                case "1":
                    $time = "submitted " . $standard->time_1;
                    break;
            }

            $parsed = $this->get_string_between($this->selectedTarget->target, '%', 'with');

            if ($parsed == "") {
                $parsed = $this->selectedTarget->target;
            }

            $accomplishment = $accomplishment . " " . $parsed . " " . $qua . " " . $time;

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

            Rating::create([
                'accomplishment' => $accomplishment,
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
            $qua = "";
            $time = "";
            $accomplishment = $this->output_finished . "/" . $this->targetOutput;
            $standard = $this->selectedTarget->standards()->first();
            
            switch($this->quality) {
                case "5":
                        if (str_contains($standard->qua_5, "with")) {
                            $qua = $standard->qua_5;
                        } else {
                            $qua = "with " .  $standard->qua_5;
                        }
                    break;
                case "4":
                        if (str_contains($standard->qua_4, "with")) {
                            $qua = $standard->qua_4;
                        } else {
                            $qua = "with " .  $standard->qua_4;
                        }
                    break;
                case "3":
                        if (str_contains($standard->qua_3, "with")) {
                            $qua = $standard->qua_3;
                        } else {
                            $qua = "with " .  $standard->qua_3;
                        }
                    break;
                case "2":
                        if (str_contains($standard->qua_2, "with")) {
                            $qua = $standard->qua_2;
                        } else {
                            $qua = "with " .  $standard->qua_2;
                        }
                    break;
                case "1":
                        if (str_contains($standard->qua_1, "with")) {
                            $qua = $standard->qua_1;
                        } else {
                            $qua = "with " .  $standard->qua_1;
                        }
                    break;
            }

            switch ($this->timeliness) {
                case "5":
                    $time = "submitted " . $standard->time_5;
                    break;
                case "4":
                    $time = "submitted " . $standard->time_4;
                    break;
                case "3":
                    $time = "submitted " . $standard->time_3;
                    break;
                case "2":
                    $time = "submitted " . $standard->time_2;
                    break;
                case "1":
                    $time = "submitted " . $standard->time_1;
                    break;
            }

            $parsed = $this->get_string_between($this->selectedTarget->target, '%', 'with');

            if ($parsed == "") {
                $parsed = $this->selectedTarget->target;
            }

            $accomplishment = $accomplishment . " " . $parsed . " " . $qua . " " . $time;

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

            Rating::where('id', $this->rating_id)->update([
                'accomplishment' => $accomplishment,
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

            if (empty($this->review_id) && empty($this->approve_id)) {
                $this->dispatchBrowserEvent('toastify', [
                    'message' => "No Head found!",
                    'color' => "#f3616d",
                ]);
                return;
            }

            $approval = Approval::create([
                'name' => $type,
                'user_id' => auth()->user()->id,
                'review_id' => $this->review_id,
                'approve_id' => $this->approve_id,
                'type' => 'ipcr',
                'user_type' => 'faculty',
                'duration_id' => $this->duration->id
            ]);
            
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
    }

    /////////////////////////// SUBMITION OF IPCR END ///////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------//

    /////////////////////////// SUBFUNCTION/OUTPUT/SUBOUTPUT/TARGET CONFIGURATION ///////////////////////////

    public function selectIpcr($type, $id, $category = null) {
        $this->selected = $type;
        switch($type) {
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

        switch ($this->selected) {
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
        foreach (auth()->user()->account_types as $account_type){
            if (str_contains(strtolower($account_type->account_type), 'no')){
                    $this->getIpcr();
                    return;
            }
        }
        $this->add = true;
    }

    public function getIpcr() {
        $this->add = false;
        $target_ids = [];
        $suboutput_ids = [];
        $output_ids = [];
        $sub_funct_ids = [];

        $selected_targets = 0;

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

        foreach (Funct::all() as $funct) {
            if (str_contains(strtolower($funct->funct), "core")) { 
                foreach ($funct->sub_functs()->orderBy('created_at', 'DESC')->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $this->duration->id)->get() as $sub_funct) {
                    foreach ($sub_funct->outputs()->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $this->duration->id)->get() as $output) {
                        foreach ($output->suboutputs as $suboutputs) {
                            foreach ($suboutputs->targets as $target) {
                                if (in_array($target->id, $target_ids)) {
                                    $selected_targets++;
                                }
                            }
                        }
                        foreach ($output->targets as $target) {
                            if (in_array($target->id, $target_ids)) {
                                $selected_targets++;
                            }
                        }
                    }
                    break;
                }
                break;
            }
        }

        $sub_percent1 = ($selected_targets/18)*100;
        $sub_percent2 = 100 - $sub_percent1;

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

        $first = true;
        foreach (array_unique($sub_funct_ids) as $id) {
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

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#435ebe",
        ]);
        $this->mount();
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
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
