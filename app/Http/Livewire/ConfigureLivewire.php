<?php

namespace App\Http\Livewire;

use App\Models\Office;
use Livewire\Component;
use App\Models\Duration;
use App\Models\Institute;
use App\Models\PrintImage;
use App\Models\AccountType;
use Livewire\WithPagination;
use App\Models\StandardValue;
use Livewire\WithFileUploads;
use App\Models\ScoreEquivalent;

class ConfigureLivewire extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $searchoffice = '';
    public $sortOffice = 'id';
    public $ascOffice = 'asc';
    public $pageOffice = 10;
    public $searchacctype = '';
    public $sortAccType = 'id';
    public $ascAccType = 'asc';
    public $pageAccType = 10;
    public $type;
    public $category;
    public $office_id;
    public $office_name;
    public $office_abbr;
    public $parent_id;
    public $account_type_id;
    public $account_type;
    public $scoreEq_id;
    public $out_from;
    public $out_to;
    public $verysat_from;
    public $verysat_to;
    public $sat_from;
    public $sat_to;
    public $unsat_from;
    public $unsat_to;
    public $poor_from;
    public $poor_to;
    public $standardValue_id;
    public $efficiency;
    public $quality;
    public $timeliness;

    public $searchinstitute = '';
    public $sortInstitute = 'id';
    public $ascInstitute = 'asc';
    public $pageInstitute = 10;
    public $institute_name;
    public $institute_id;

    public $printImage_id;
    public $header;
    public $footer;
    public $form;
    public $itteration = 1;

    protected $rules = [
        'office_name' => ['required_if:type,office'],
        'office_abbr' => ['required_if:type,office'],
        'account_type' => ['required_if:type,account_type'],
        'out_from' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gt:verysat_to'],
        'out_to' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gte:out_from'],
        'verysat_from' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gt:sat_to'],
        'verysat_to' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gte:verysat_from'],
        'sat_from' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gt:unsat_to'],
        'sat_to' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gte:sat_from'],
        'unsat_from' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gt:poor_to'],
        'unsat_to' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gte:unsat_from'],
        'poor_from' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gte:1'],
        'poor_to' => ['nullable', 'required_if:type,scoreEq', 'numeric', 'max:5', 'gte:poor_from'],
        'efficiency' => ['required_if:type,standardValue'],
        'quality' => ['required_if:type,standardValue'],
        'timeliness' => ['required_if:type,standardValue'],

        'institute_name' => ['required_if:type,institute'],
        
        'header' => ['nullable'],
        'footer' => ['nullable'],
        'form' => ['nullable'],
    ];

    protected $messages = [
        'office_name.required_if' => 'Office Name cannot be null.',
        'office_abbr.required_if' => 'Office Abbreviation cannot be null.',
        'account_type.required_if' => 'Account Type cannot be null.',

        'out_from.required_if' => 'Outstanding Score From cannot be null.',
        'out_from.numeric' => 'Outstanding Score From should be numeric.',
        'out_from.max' => 'Outstanding Score From should not exceed 5.',
        'out_from.gt' => 'Outstanding Score From should be greater than Very Satisfacty Score.',

        'out_to.required_if' => 'Outstanding Score To cannot be null.',
        'out_to.numeric' => 'Outstanding Score To should be numeric.',
        'out_to.max' => 'Outstanding Score To should not exceed 5.',
        'out_to.gte' => 'Outstanding Score To should be greater than or equal to Outstanding Score From.',

        'verysat_from.required_if' => 'Very Satisfactory Score From cannot be null.',
        'verysat_from.numeric' => 'Very Satisfactory Score From should be numeric.',
        'verysat_from.max' => 'Very Satisfactory Score From should not exceed 5.',
        'verysat_from.gt' => 'Very Satisfactory Score From should be greater than Satisfactory Score.',

        'verysat_to.required_if' => 'Very Satisfactory Score To cannot be null.',
        'verysat_to.numeric' => 'Very Satisfactory Score To should be numeric.',
        'verysat_to.max' => 'Very Satisfactory Score To should not exceed 5.',
        'verysat_to.gte' => 'Very Satisfactory Score To should be greater than or equal to Very Satisfactory Score From.',

        'sat_from.required_if' => 'Satisfactory Score From cannot be null.',
        'sat_from.numeric' => 'Satisfactory Score From should be numeric.',
        'sat_from.max' => 'Satisfactory Score From should not exceed 5.',
        'sat_from.gt' => 'Satisfactory Score From should be greater than Unsatisfactory Score.',

        'sat_to.required_if' => 'Satisfactory Score To cannot be null.',
        'sat_to.numeric' => 'Satisfactory Score To should be numeric.',
        'sat_to.max' => 'Satisfactory Score To should not exceed 5.',
        'sat_to.gte' => 'Satisfactory Score To should be greater than equal to Satisfactory Score From.',

        'unsat_from.required_if' => 'Unsatisfactory Score From cannot be null.',
        'unsat_from.numeric' => 'Unsatisfactory Score From should be numeric.',
        'unsat_from.max' => 'Unsatisfactory Score From should not exceed 5.',
        'unsat_from.gt' => 'Unsatisfactory Score From should be greater than Poor Score.',

        'unsat_to.required_if' => 'Unsatisfactory Score To cannot be null.',
        'unsat_to.numeric' => 'Unsatisfactory Score To should be numeric.',
        'unsat_to.max' => 'Unsatisfactory Score To should not exceed 5.',
        'unsat_to.gte' => 'Unsatisfactory Score To should be greater than equal to Unsatisfactory Score From.',

        'poor_from.required_if' => 'Poor Score From cannot be null.',
        'poor_from.numeric' => 'Poor Score From should be numeric.',
        'poor_from.max' => 'Poor Score From should not exceed 5.',
        'poor_from.gte' => 'Poor Score From should be greater than or equal to 1.',

        'poor_to.required_if' => 'Poor Score To cannot be null.',
        'poor_to.numeric' => 'Poor Score To should be numeric.',
        'poor_to.max' => 'Poor Score To should not exceed 5.',
        'poor_to.gte' => 'Poor Score To should be greater than equal to Poor Score From.',

        'efficiency.required_if' => 'Efficiency cannot be null.',
        'quality.required_if' => 'Quality cannot be null.',
        'timeliness.required_if' => 'Timeliness cannot be null.',

        'institute_name.required_if' => 'Institute Name cannot be null.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function render()
    {
        $offices = Office::query();
        if ($this->searchoffice) {
            $search = $this->searchoffice;
            $offices->whereHas('parent', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                    return $query->where('office_name', 'like', "%{$search}%");
                })
                ->orwhere('office_name', 'like', "%{$search}%")
                ->orwhere('office_abbr', 'like', "%{$search}%");
        }

        $account_types = AccountType::query();
        if ($this->searchacctype) {
            $account_types->where('account_type', 'like', "%{$this->searchacctype}%");
        }

        $institutes = Institute::query();
        if ($this->searchinstitute) {
            $search = $this->searchinstitute;
            $institutes->whereHas('office', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                    return $query->where('office_name', 'like', "%{$search}%");
                })
                ->orwhere('institute_name', 'like', "%{$search}%");
        }

        return view('livewire.configure-livewire',[
            'offices' => $offices->orderBy($this->sortOffice, $this->ascOffice)->paginate($this->pageOffice),
            'account_types' => $account_types->orderBy($this->sortAccType, $this->ascAccType)->paginate($this->pageAccType),
            'durations' => Duration::orderBy('id', 'desc')->paginate(10),
            'scoreEq' => ScoreEquivalent::first(),
            'standardValue' => StandardValue::first(),
            'allOffices' => Office::all(),
            'printImage' => PrintImage::first(),
            'institutes' => $institutes->orderBy($this->sortInstitute, $this->ascInstitute)->paginate($this->pageInstitute),
        ]);
    }

    public function save(){
        $this->validate();

        if ($this->category == 'edit' && $this->type == 'office') {
            Office::where('id', $this->office_id)->update([
                'office_name' => $this->office_name,
                'office_abbr' => $this->office_abbr, 
                'parent_id' => $this->parent_id
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        } elseif ($this->type == 'office') {
            Office::create([
                'office_name' => $this->office_name, 
                'office_abbr' => $this->office_abbr,
                'parent_id' => $this->parent_id
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        } elseif ($this->category == 'edit' && $this->type == 'account_type') {
            AccountType::where('id', $this->account_type_id)->update([
                'account_type' => $this->account_type,
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        } elseif ($this->type == 'account_type') {
            AccountType::create([
                'account_type' => $this->account_type,
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        } elseif ($this->category == 'edit' && $this->type == 'standardValue') {
            StandardValue::where('id', $this->standardValue_id)->update([
                'efficiency' => $this->efficiency,
                'quality' => $this->quality,
                'timeliness' => $this->timeliness,
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        } elseif ($this->type == 'scoreEq' && $this->category == 'edit') {
            ScoreEquivalent::where('id', $this->scoreEq_id)->update([
                'out_from' => $this->out_from,
                'out_to' => $this->out_to,
                'verysat_from' => $this->verysat_from,
                'verysat_to' => $this->verysat_to,
                'sat_from' => $this->sat_from,
                'sat_to' => $this->sat_to,
                'unsat_from' => $this->unsat_from,
                'unsat_to' => $this->unsat_to,
                'poor_from' => $this->poor_from,
                'poor_to' => $this->poor_to,
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        } elseif ($this->category == 'edit' && $this->type == 'institute') {
            Institute::where('id', $this->institute_id)->update([
                'institute_name' => $this->institute_name,
                'office_id' => $this->office_id
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        } elseif ($this->type == 'institute') {
            Institute::create([
                'institute_name' => $this->institute_name, 
                'office_id' => $this->office_id
            ]);

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Added Successfully",
                'color' => "#435ebe",
            ]);
        } elseif ($this->category == 'edit' && $this->type == 'printImage') {

            if ($this->header) {
                $header = $this->header->store('images');

                PrintImage::where('id', $this->printImage_id)->update([
                    'header_link' => $header,
                ]);
            }

            if ($this->footer) {
                $footer = $this->footer->store('images');
    
                PrintImage::where('id', $this->printImage_id)->update([
                    'footer_link' => $footer,
                ]);
            }

            if ($this->form) {
                $form = $this->form->store('images');

                PrintImage::where('id', $this->printImage_id)->update([
                    'form_link' => $form,
                ]);
            }

            $this->dispatchBrowserEvent('toastify', [
                'message' => "Updated Successfully",
                'color' => "#28ab55",
            ]);
        }

        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }

    public function select($type, $id = null, $category = null){
        $this->type = $type;

        if ($type == 'office') {
            $this->office_id = $id;
            if ($category == 'edit') {
                $this->category = $category;

                $data = Office::find($this->office_id);

                $this->office_name = $data->office_name;
                $this->office_abbr = $data->office_abbr;
                $this->parent_id = $data->parent_id;
            }
        } elseif ($type == 'account_type') {
            $this->account_type_id = $id;
            if ($category == 'edit') {
                $this->category = $category;

                $data = AccountType::find($this->account_type_id);

                $this->account_type = $data->account_type;
            }
        } elseif ($type == 'standardValue') {
            $this->standardValue_id = $id;
            if ($category == 'edit') {
                $this->category = $category;

                $data = StandardValue::find($this->standardValue_id);

                $this->efficiency = $data->efficiency;
                $this->quality = $data->quality;
                $this->timeliness = $data->timeliness;
            }
        } elseif ($type == 'scoreEq') {
            $this->scoreEq_id = $id;
            $this->category = $category;

            $data = ScoreEquivalent::find($this->scoreEq_id);

            $this->out_from = $data->out_from;
            $this->out_to = $data->out_to;
            $this->verysat_from = $data->verysat_from;
            $this->verysat_to = $data->verysat_to;
            $this->sat_from = $data->sat_from;
            $this->sat_to = $data->sat_to;
            $this->unsat_from = $data->unsat_from;
            $this->unsat_to = $data->unsat_to;
            $this->poor_from = $data->poor_from;
            $this->poor_to = $data->poor_to;
        } elseif ($type == 'institute') {
            $this->institute_id = $id;
            if ($category == 'edit') {
                $this->category = $category;

                $data = Institute::find($this->institute_id);

                $this->institute_name = $data->institute_name;
                $this->office_id = $data->office_id;
            }
        } elseif ($type == 'printImage') {
            $this->printImage_id = $id;
            if ($category == 'edit') {
                $this->category = $category;
            }
        }
    }

    public function delete(){
        if($this->type == 'office') {
            Office::where('id', $this->office_id)->delete();
        } elseif ($this->type == 'account_type') {
            AccountType::where('id', $this->account_type_id)->delete();
        } elseif ($this->type == 'institute') {
            Institute::where('id', $this->institute_id)->delete();
        }

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }
    
    public function resetInput(){
        $this->office_id = '';
        $this->office_name = '';
        $this->office_abbr = '';
        $this->parent_id = '';
        $this->type = '';
        $this->category = '';
        $this->account_type_id = '';
        $this->account_type = '';
        $this->sortOffice = 'id';
        $this->ascOffice = 'asc';
        $this->pageOffice = 10;
        $this->sortAccType = 'id';
        $this->ascAccType = 'asc';
        $this->pageAccType = 10;
        $this->out_from = '';
        $this->out_to = '';
        $this->verysat_from = '';
        $this->verysat_to = '';
        $this->sat_from = '';
        $this->sat_to = '';
        $this->unsat_from = '';
        $this->unsat_to = '';
        $this->poor_from = '';
        $this->poor_to = '';

        $this->institute_name = '';
        $this->institute_id = '';

        $this->printImage_id = '';
        $this->header = '';
        $this->footer = '';
        $this->form = '';
        $this->itteration++;
    }

    public function closeModal(){
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal'); 
    }
}
