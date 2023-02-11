<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Training;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TrainingLivewire extends Component
{
    use WithPagination;

    public $training_name;
    public $links;
    public $keywords;
    public $training_id;
    public $search;
    public $targets;

    protected  $queryString = ['search'];

    protected $rules = [
        'training_name' => ['required'],
        'links' => ['required'],
        'keywords' => ['required'],
    ];

    protected $messages = [
        'training_name' => "Training Name cannot be null.",
        'links' => "Links cannot be null.",
        'keywords' => "Keywords cannot be null.",
    ];

    public function render()
    {
        $searches = preg_split('/\s+/', $this->search);
        $trainings = Training::query();

        if ($searches) {
            foreach ($searches as $search) {
                $trainings->orWhere('training_name', 'LIKE', '%' . $search . '%')
                ->orWhere('keywords', 'LIKE', '%' . $search . '%');
            }
        }
        
        $trainings = $trainings->orderBy('id', 'DESC')->distinct()->paginate(25);

        return view('livewire.training-livewire',[
            'trainings' => $trainings,
        ]);
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save() {
        $this->validate();

        Training::create([
            'training_name' => $this->training_name,
            'links' => $this->links,
            'keywords' => $this->keywords,
            'user_id' => Auth::user()->id
        ]);

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Added Successfully",
            'color' => "#435ebe",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function update() {
        $this->validate();

        Training::where('id', $this->training_id)->update([
            'training_name' => $this->training_name,
            'links' => $this->links,
            'keywords' => $this->keywords,
            'user_id' => Auth::user()->id
        ]);

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Updated Successfully",
            'color' => "#28ab55",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete() {
        Training::where('id', $this->training_id)->delete();

        $this->dispatchBrowserEvent('toastify', [
            'message' => "Deleted Successfully",
            'color' => "#f3616d",
        ]);
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function clicked($id, $category = null) {
        $this->training_id = $id;

        if($category) {
            $training = Training::find($id);

            $this->training_name = $training->training_name;
            $this->links = $training->links;
            $this->keywords = $training->keywords;
        }
    }

    public function resetInput()
    {
        $this->training_name = '';
        $this->links = '';
        $this->keywords = '';
        $this->training_id = '';
        $this->search;
    }

    public function closeModal()
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }
}
