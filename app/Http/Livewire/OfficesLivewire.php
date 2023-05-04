<?php

namespace App\Http\Livewire;

use App\Models\Office;
use Livewire\Component;
use App\Models\Institute;

class OfficesLivewire extends Component
{

    public $office_selected;
    public $institute_id;
    public $institute;

    public function render()
    {
        $institutes = [];

        if (isset($this->office_selected[0])) {
            foreach ($this->office_selected as $office_id) {
                $office = Office::find($office_id);

                foreach ($office->institutes as $institute) {
                    array_push($institutes, $institute);
                }
            }

            array_unique($institutes);
        }

        if (in_array($this->institute, $institutes)) {
            $this->institute_id = $this->institute;
        }
        
        return view('livewire.offices-livewire',[
            'offices' => Office::orderBy('office_name', 'ASC')->get(),
            'institutes' => $institutes
        ]);
    }

    public function institute_set() {
        $this->institute = Institute::find($this->institute_id);
    }
}
