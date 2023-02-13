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

class StaffLivewire extends Component
{
    use WithPagination;

    public $selected = 'output';
    public $approval;
    public $approvalStandard;
    public $assess;
    public $review_user;
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

    public $targetOutput;


    protected $listeners = ['percentage', 'resetIntput'];

    protected $rules = [
        'percent.core' => ['required_if:selected,percent'],
        'percent.strategic' => ['required_if:selected,percent'],
        'percent.support' => ['required_if:selected,percent'],

        'sub_funct' => ['required_if:selected,sub_funct'],
        'output' => ['required_if:selected,output'],
        'output_id' => ['required_if:selected,suboutput'],
        'suboutput' => ['required_if:selected,suboutput'],
        'subput' => ['nullable', 'required_if:selected,target_id'],
        'target' => ['required_if:selected,target'],
        'target_output' => ['nullable', 'required_if:selected,target_output', 'numeric'],

        'review_id' => ['required_if:selected,submition'],
        'approve_id' => ['required_if:selected,submition'],

        'output_finished' => ['required_if:selected,rating'],
        'efficiency' => ['required_without_all:quality,timeliness'],
        'quality' => ['required_without_all:efficiency,timeliness'],
        'timeliness' => ['required_without_all:efficiency,quality'],
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

        'review_id' => 'Reviewer cannot be null',
        'approve_id' => 'Approver cannot be null',

        'output_finished.require_if' => 'Output Finished cannot be null.',
        'efficiency.require_without_all' => 'Efficiency cannot be null if Quality and Timeliness is null.',
        'quality.require_without_all' => 'Quality cannot be null if Efficiency and Timeliness is null.',
        'timeliness.require_without_all' => 'Timeliness cannot be null if Efficiency and Quality is null.',
    ];
    
    public function updated($property)
    {
        $this->validateOnly($property);
    }


    public function mount() {
        $this->duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $this->percentage = auth()->user()->percentages()->where('type', 'ipcr')->where('user_type', 'staff')->first();
        if ($this->duration) {
            $this->approval = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'staff')->first();
            $this->approvalStandard = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'standard')->where('duration_id', $this->duration->id)->where('user_type', 'staff')->first();
            $this->assess = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'ipcr')->where('duration_id', $this->duration->id)->where('user_type', 'staff')->first();
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
        }

        $depths;

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
            $accomplishment = $this->output_finished . "/" . $this->selectedTarget->target_output;
            $standard = $this->selectedTarget->standards()->where('user_id', auth()->user()->id)->first();
            
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

            session()->flash('message', 'Added Successfully!');
        } elseif ($category == 'edit') {
            $divisor = 0;
            $qua = "";
            $time = "";
            $accomplishment = $this->output_finished . "/" . $this->selectedTarget->target_output;
            $standard = $this->selectedTarget->standards()->where('user_id', auth()->user()->id)->first();
            
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

            session()->flash('message', 'Updated Successfully!');
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

            $approval = Approval::create([
                'name' => $type,
                'user_id' => auth()->user()->id,
                'review_id' => $this->review_id,
                'approve_id' => $this->approve_id,
                'type' => 'ipcr',
                'user_type' => 'staff',
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
                    'duration_id' => $this->duration->id
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
                        'duration_id' => $this->duration->id
                    ]));
                    break;
                }
                auth()->user()->outputs()->attach(Output::create([
                    'code' => $code,
                    'output' => $this->output,
                    'type' => 'ipcr',
                    'user_type' => 'staff',
                    'funct_id' => $this->funct_id,
                    'duration_id' => $this->duration->id
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
                        'duration_id' => $this->duration->id
                    ]));
                } elseif ($subput[0] == 'suboutput') {
                    auth()->user()->targets()->attach(Target::create([
                        'target' => $this->target,
                        'suboutput_id' => $subput[1],
                        'duration_id' => $this->duration->id
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
                    'target' => $this->target
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

        $this->checkPercentage();

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
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
