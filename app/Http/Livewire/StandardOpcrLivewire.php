<?php

namespace App\Http\Livewire;

use App\Models\Pmt;
use App\Models\User;
use App\Models\Funct;
use App\Models\Office;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use App\Models\Standard;
use App\Models\Percentage;
use Livewire\WithPagination;
use App\Models\StandardValue;
use App\Models\SubPercentage;
use App\Notifications\ApprovalNotification;

class StandardOpcrLivewire extends Component
{
    use WithPagination;

    public $eff_5;
    public $eff_4;
    public $eff_3;
    public $eff_2;
    public $eff_1;
    public $qua_5;
    public $qua_4;
    public $qua_3;
    public $qua_2;
    public $qua_1;
    public $time_5;
    public $time_4;
    public $time_3;
    public $time_2;
    public $time_1;
    public $dummy = 'dummy';
    public $target_id;
    public $standard_id;
    public $selected;
    public $approval;
    public $duration;
    public $targ;
    public $percentage;
    
    public $review_id;
    public $approve_id;
    public $highestOffice;
    public $review_user;
    public $approve_user;

    public $hasStandard = false;

    protected $rules = [
        'eff_5' => ['nullable', 'required_without_all:eff_4,eff_3,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'eff_4' => ['nullable', 'required_without_all:eff_5,eff_3,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'eff_3' => ['nullable', 'required_without_all:eff_5,eff_4,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'eff_2' => ['nullable', 'required_without_all:eff_5,eff_4,eff_3,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'eff_1' => ['nullable', 'required_without_all:eff_5,eff_4,eff_3,eff_2,qua_5,qua_4,qua_3,qua_2,qua_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'qua_5' => ['nullable', 'required_without_all:qua_4,qua_3,qua_2,qua_1,eff_5,eff_4,eff_3,eff_2,eff_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'qua_4' => ['nullable', 'required_without_all:qua_5,qua_3,qua_2,qua_1,eff_5,eff_4,eff_3,eff_2,eff_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'qua_3' => ['nullable', 'required_without_all:qua_5,qua_4,qua_2,qua_1,eff_5,eff_4,eff_3,eff_2,eff_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'qua_2' => ['nullable', 'required_without_all:qua_5,qua_4,qua_3,qua_1,eff_5,eff_4,eff_3,eff_2,eff_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'qua_1' => ['nullable', 'required_without_all:qua_5,qua_4,qua_3,qua_2,eff_5,eff_4,eff_3,eff_2,eff_1,time_5,time_4,time_3,time_2,time_1,dummy'],
        'time_5' => ['nullable', 'required_without_all:time_4,time_3,time_2,time_1,eff_5,eff_4,eff_3,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,dummy'],
        'time_4' => ['nullable', 'required_without_all:time_5,time_3,time_2,time_1,eff_5,eff_4,eff_3,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,dummy'],
        'time_3' => ['nullable', 'required_without_all:time_5,time_4,time_2,time_1,eff_5,eff_4,eff_3,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,dummy'],
        'time_2' => ['nullable', 'required_without_all:time_5,time_4,time_3,time_1,eff_5,eff_4,eff_3,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,dummy'],
        'time_1' => ['nullable', 'required_without_all:time_5,time_4,time_3,time_2,eff_5,eff_4,eff_3,eff_2,eff_1,qua_5,qua_4,qua_3,qua_2,qua_1,dummy'],
    ];

    protected $messages = [
        'eff_5.required_without_all' => 'Efficiency score 5 cannot be null.',
        'eff_4.required_without_all' => 'Efficiency score 4 cannot be null.',
        'eff_3.required_without_all' => 'Efficiency score 3 cannot be null.',
        'eff_2.required_without_all' => 'Efficiency score 2 cannot be null.',
        'eff_1.required_without_all' => 'Efficiency score 1 cannot be null.',
        'qua_5.required_without_all' => 'Quality score 5 cannot be null.',
        'qua_4.required_without_all' => 'Quality score 4 cannot be null.',
        'qua_3.required_without_all' => 'Quality score 3 cannot be null.',
        'qua_2.required_without_all' => 'Quality score 2 cannot be null.',
        'qua_1.required_without_all' => 'Quality score 1 cannot be null.',
        'time_5.required_without_all' => 'Timeliness score 5 cannot be null.',
        'time_4.required_without_all' => 'Timeliness score 4 cannot be null.',
        'time_3.required_without_all' => 'Timeliness score 3 cannot be null.',
        'time_2.required_without_all' => 'Timeliness score 2 cannot be null.',
        'time_1.required_without_all' => 'Timeliness score 1 cannot be null.',
    ];

    public function mount(){ 
        $this->duration = Duration::orderBy('id', 'DESC')->where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->first();
        if ($this->duration) {
            $this->percentage = Percentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->where('duration_id', $this->duration->id)->first();
            $this->sub_percentages = SubPercentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->where('duration_id', $this->duration->id)->get();
        
            $this->approval = auth()->user()->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'standard')->where('duration_id', $this->duration->id)->where('user_type', 'office')->first();
            if ($this->approval) {
                foreach ($this->approval->reviewers as $reviewer) {
                    if ($reviewer->pivot->review_message) {
                        $this->review_user['name'] = $reviewer->name;
                        $this->review_user['message'] = $reviewer->pivot->review_message;
                    }
                }

                $this->approve_user['name'] = User::where('id', $this->approval->approve_id)->pluck('name')->first();
                $this->approve_user['message'] = $this->approval->approve_message;
            }
        }
    }

    public function render()
    {
        foreach (auth()->user()->targets as $target) {
            if (($target->suboutput_id && $target->suboutput->output->user_type == 'office') || ($target->output_id && $target->output->user_type == 'office')) {
                if (count($target->standards) > 0) {
                    foreach ($target->standards as $standard) {
                        if ($standard->user_id == auth()->user()->id || $standard->user_id === null) {
                            $this->hasStandard = true;
                            break;
                        } else {
                            $this->hasStandard = false;
                        }
                    }
                    if (!$this->hasStandard) {
                        break;
                    }
                } else {
                    $this->hasStandard = false;
                    break;
                }
            }
        }

        $functs = Funct::paginate(1);
        return view('livewire.standard-opcr-livewire', [
            'functs' => $functs,
            'standardValue' => StandardValue::first()
        ]);
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

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
            $this->review_id[$indi_pmt->user->id] = $indi_pmt->user->id; 
        }

        $approval = Approval::create([
            'name' => $type,
            'user_id' => auth()->user()->id,
            'approve_id' => $this->approve_id,
            'type' => 'standard',
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

    public function save($category){
        $this->validate();

        $standardValue = StandardValue::first();

        $effs = preg_split('/\r\n|\r|\n/', $standardValue->efficiency);
        $quas = preg_split('/\r\n|\r|\n/', $standardValue->quality);
        $times = preg_split('/\r\n|\r|\n/', $standardValue->timeliness);

        $efficiency = $standardValue->efficiency;
        $quality = $standardValue->quality;
        $timeliness = $standardValue->timeliness;

        if (!in_array($this->eff_5, $effs) && $this->eff_5 != '') {
            $efficiency .= "\r\n" . $this->eff_5;
        }
        if (!in_array($this->eff_4, $effs) && $this->eff_4 != '') {
            $efficiency .= "\r\n" . $this->eff_4;
        }
        if (!in_array($this->eff_3, $effs) && $this->eff_3 != '') {
            $efficiency .= "\r\n" . $this->eff_3;
        }
        if (!in_array($this->eff_2, $effs) && $this->eff_2 != '') {
            $efficiency .= "\r\n" . $this->eff_2;
        }
        if (!in_array($this->eff_1, $effs) && $this->eff_1 != '') {
            $efficiency .= "\r\n" . $this->eff_1;
        }
        
        if (!in_array($this->qua_5, $quas) && $this->qua_5 != '') {
            $quality .= "\r\n" . $this->qua_5;
        }
        if (!in_array($this->qua_4, $quas) && $this->qua_4 != '') {
            $quality .= "\r\n" . $this->qua_4;
        }
        if (!in_array($this->qua_3, $quas) && $this->qua_3 != '') {
            $quality .= "\r\n" . $this->qua_3;
        }
        if (!in_array($this->qua_2, $quas) && $this->qua_2 != '') {
            $quality .= "\r\n" . $this->qua_2;
        }
        if (!in_array($this->qua_1, $quas) && $this->qua_1 != '') {
            $quality .= "\r\n" . $this->qua_1;
        }
        
        if (!in_array($this->time_5, $times) && $this->time_5 != '') {
            $timeliness .= "\r\n" . $this->time_5;
        }
        if (!in_array($this->time_4, $times) && $this->time_4 != '') {
            $timeliness .= "\r\n" . $this->time_4;
        }
        if (!in_array($this->time_3, $times) && $this->time_3 != '') {
            $timeliness .= "\r\n" . $this->time_3;
        }
        if (!in_array($this->time_2, $times) && $this->time_2 != '') {
            $timeliness .= "\r\n" . $this->time_2;
        }
        if (!in_array($this->time_1, $times) && $this->time_1 != '') {
            $timeliness .= "\r\n" . $this->time_1;
        }

        StandardValue::where('id', 1)->update([
            'efficiency' => $efficiency,
            'quality' => $quality,
            'timeliness' => $timeliness,
        ]);

        if ($category == 'add'){
            Standard::create([
                'eff_5' => $this->eff_5,
                'eff_4' => $this->eff_4,
                'eff_3' => $this->eff_3,
                'eff_2' => $this->eff_2,
                'eff_1' => $this->eff_1,
                'qua_5' => $this->qua_5,
                'qua_4' => $this->qua_4,
                'qua_3' => $this->qua_3,
                'qua_2' => $this->qua_2,
                'qua_1' => $this->qua_1,
                'time_5' => $this->time_5,
                'time_4' => $this->time_4,
                'time_3' => $this->time_3,
                'time_2' => $this->time_2,
                'time_1' => $this->time_1,
                'target_id' => $this->target_id,
                'duration_id' => $this->duration->id,
                'user_id' => auth()->user()->id
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        } elseif ($category == 'edit'){
            Standard::where('id', $this->standard_id)->update([
                'eff_5' => $this->eff_5,
                'eff_4' => $this->eff_4,
                'eff_3' => $this->eff_3,
                'eff_2' => $this->eff_2,
                'eff_1' => $this->eff_1,
                'qua_5' => $this->qua_5,
                'qua_4' => $this->qua_4,
                'qua_3' => $this->qua_3,
                'qua_2' => $this->qua_2,
                'qua_1' => $this->qua_1,
                'time_5' => $this->time_5,
                'time_4' => $this->time_4,
                'time_3' => $this->time_3,
                'time_2' => $this->time_2,
                'time_1' => $this->time_1,
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        }

        return redirect(request()->header('Referer'));
    }

    public function delete(){
        Standard::find($this->standard_id)->delete();
        
        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }

    public function clicked($category, $id){
        $this->dummy = '';
        // If add $id = $target_id else $id = $standard_id
        if ($category == 'add'){
            $this->target_id = $id;
        } elseif ($category == 'edit'){
            $this->standard_id = $id;
            $standard = Standard::find($id);
            $this->eff_5 = $standard->eff_5;
            $this->eff_4 = $standard->eff_4;
            $this->eff_3 = $standard->eff_3;
            $this->eff_2 = $standard->eff_2;
            $this->eff_1 = $standard->eff_1;
            $this->qua_5 = $standard->qua_5;
            $this->qua_4 = $standard->qua_4;
            $this->qua_3 = $standard->qua_3;
            $this->qua_2 = $standard->qua_2;
            $this->qua_1 = $standard->qua_1;
            $this->time_5 = $standard->time_5;
            $this->time_4 = $standard->time_4;
            $this->time_3 = $standard->time_3;
            $this->time_2 = $standard->time_2;
            $this->time_1 = $standard->time_1;
        } elseif ($category == 'delete'){
            $this->standard_id = $id;
        }
    }

    public function resetInput(){
        $this->eff_5 = '';
        $this->eff_4 = '';
        $this->eff_3 = '';
        $this->eff_2 = '';
        $this->eff_1 = '';
        $this->qua_5 = '';
        $this->qua_4 = '';
        $this->qua_3 = '';
        $this->qua_2 = '';
        $this->qua_1 = '';
        $this->time_5 = '';
        $this->time_4 = '';
        $this->time_3 = '';
        $this->time_2 = '';
        $this->time_1 = '';
        $this->target_id = '';
        $this->standard_id = '';
        $this->selected = '';
        $this->superior1_id = '';
        $this->superior2_id = '';
        $this->dummy = 'dummy';
        $this->review_id = '';
        $this->approve_id = '';
    }

    public function closeModal(){
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }
}
