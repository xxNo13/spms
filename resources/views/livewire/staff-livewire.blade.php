<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Individual Performance Commitment and Review</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a
                                href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">IPCR - Staff</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
            
        {{-- Message for declining --}}
        {{-- <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Name Message:
                    </h4>
                    <p class="text-subtitle text-muted"></p>
                </div>
                <div class="card-body">
                    Message
                </div>
            </div>
        </div> --}}

        @foreach ($functs as $funct)
            @php
                $number = 1;
            @endphp
            <div class="">
                <div class="hstack mb-3 gap-2">
                    <h4>
                        {{ $funct->funct }}
                        @if ($percentage)
                            @switch($funct->funct)
                                @case('Core Function')
                                    {{ $percentage->core }}%
                                    @break
                                @case('Strategic Function')
                                    {{ $percentage->strategic }}%
                                    @break
                                @case('Support Function')
                                    {{ $percentage->support }}%
                                    @break
                            @endswitch
                        @endif
                    </h4>
                    <div class="ms-auto hstack gap-3">
                        @if (($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d')))
                            @if (!$percentage)
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#AddPercentageModal" title="Add Percentage" wire:click="percentage">
                                    Add Percentage
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#EditPercentageModal" title="Edit Percentage" wire:click="$emit('percentage', 'edit')">
                                    Edit Percentage
                                </button>
                            @endif
                        @endif
        
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#AddIPCROSTModal" title="Add Output/Suboutput/Target">
                            Add OST
                        </button>
        
                        {{-- <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                            data-bs-target="#SubmitISOModal" title="Save IPCR" wire:click="submit">
                            Submit
                        </button> --}}
        
                        {{-- <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                            data-bs-target="#AssessISOModal" title="Save IPCR" wire:click="submit">
                            Assess
                        </button> --}}
        
                        <a href="#" target="_blank" class="btn icon btn-primary" title="Print IPCR">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>
                </div>
                @foreach (auth()->user()->sub_functs()->where('funct_id', $funct->id)->get() as $sub_funct)
                    <div>
                        <h5>
                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('sub_funct', {{$sub_funct->id}}, 'edit')">Edit</a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal"  wire:click="selectIpcr('sub_funct', {{$sub_funct->id}})">Delete</a>
                            </div>
                            {{ $sub_funct->sub_funct }}
                            @if ($sub_percentage = auth()->user()->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                {{ $sub_percentage->value }}
                            @endif
                        </h5>

                        @foreach (auth()->user()->outputs()->where('sub_funct_id', $sub_funct->id)->where('type', 'ipcr')->where('user_type', 'staff')->get() as $output)
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('output', {{$output->id}}, 'edit')">Edit</a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('output', {{$output->id}})">Delete</a>
                                        </div>
                                        {{ $output->code }} {{ $number++ }} - {{ $output->output }}
                                    </h4>
                                    <p class="text-subtitle text-muted"></p>
                                </div>

                                @forelse (auth()->user()->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                                    
                                    <div class="card-body">
                                        <h6>
                                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}}, 'edit')">Edit</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}})">Delete</a>
                                            </div>
                                            {{ $suboutput->suboutput }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion accordion-flush"
                                            id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                            <div class="d-sm-flex">
                                                @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                                    <span class="my-auto">
                                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$target->id}})">Delete</a>
                                                        </div>
                                                    </span>
                                                    <div wire:ignore.self
                                                        class="accordion-button collapsed gap-2"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                        aria-expanded="true"
                                                        aria-controls="{{ 'target' }}{{ $target->id }}"
                                                        role="button">
                                                        <span class="my-auto">
                                                            <i class="bi bi-check2"></i>
                                                        </span>
                                                        {{ $target->target }}
                                                    </div>  
                                                @endforeach
                                            </div>

                                            @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)

                                                <div wire:ignore.self
                                                    id="{{ 'target' }}{{ $target->id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                                    <div class="accordion-body table-responsive">
                                                        <table class="table table-lg text-center">
                                                            <thead>
                                                                <tr>
                                                                    <td rowspan="2">Target Output</td>
                                                                    <td rowspan="2">Actual
                                                                        Accomplishment</td>
                                                                    <td colspan="4">Rating</td>
                                                                    <td rowspan="2">Remarks</td>
                                                                    <td rowspan="2">Actions</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>E</td>
                                                                    <td>Q</td>
                                                                    <td>T</td>
                                                                    <td>A</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="white-space: nowrap;">
                                                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditTargetOutputModal"  wire:click="selectIpcr('target_output', {{$target->id}}, 'edit')">Edit</a>
                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteTargetOutputModal"  wire:click="selectIpcr('target_output', {{$target->id}})">Delete</a>
                                                                        </div>
                                                                        
                                                                            {{ $target->pivot->target_output }}
                                                                        
                                                                    </td>
                        
                                                                    {{-- <td class="text-center">
                                                                        <button type="button" class="ms-md-auto btn icon btn-primary" data-bs-toggle="modal"
                                                                            data-bs-target="#AddTargetOutputModal" title="Add Target Output">
                                                                            <i class="bi bi-plus"></i>
                                                                        </button>
                                                                    </td> --}}
                        
                                                                    @forelse ($target->ratings as $rating)
                                                                        @if ($rating->user_id == auth()->user()->id) 
                                                                            <td>{{ $rating->accomplishment }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($rating->efficiency)
                                                                                    {{ $rating->efficiency }}
                                                                                @else
                                                                                    NR
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($rating->quality)
                                                                                    {{ $rating->quality }}
                                                                                @else
                                                                                    NR
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($rating->timeliness)
                                                                                    {{ $rating->timeliness }}
                                                                                @else
                                                                                    NR
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $rating->average }}
                                                                            </td>
                                                                            <td>{{ $rating->remarks }}
                                                                            </td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="btn icon btn-success"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditRatingModal"
                                                                                    title="Edit Rating">
                                                                                    <i class="bi bi-pencil-square"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn icon btn-danger"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#DeleteModal"
                                                                                    title="Delete Rating">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                            @break
                                                                        @elseif ($loop->last)
                                                                            <td colspan="6"></td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="btn icon btn-primary"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#AddRatingModal"
                                                                                    title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                                    <i class="bi bi-plus"></i>
                                                                                </button>
                                                                            </td>
                                                                        @endif
                                                                    @empty
                                                                        <td colspan="6"></td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn icon btn-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddRatingModal"
                                                                                title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                                <i class="bi bi-plus"></i>
                                                                            </button>
                                                                        </td>
                                                                    @endforelse
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="card-body">
                                        <div class="accordion accordion-flush"
                                            id="{{ 'output' }}{{ $output->id }}">
                                            <div class="d-sm-flex">
                                                @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)
                                                    <span class="my-auto">
                                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$output->id}})">Delete</a>
                                                        </div>
                                                    </span>
                                                    <div wire:ignore.self
                                                        class="accordion-button collapsed gap-2"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                        aria-expanded="true"
                                                        aria-controls="{{ 'target' }}{{ $target->id }}"
                                                        role="button">
                                                        <span class="my-auto">
                                                            <i class="bi bi-check2"></i>
                                                        </span>
                                                        {{ $target->target }}
                                                    </div>  
                                                @endforeach
                                            </div>

                                            @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)

                                                <div wire:ignore.self
                                                    id="{{ 'target' }}{{ $target->id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                                    <div class="accordion-body table-responsive">
                                                        <table class="table table-lg text-center">
                                                            <thead>
                                                                <tr>
                                                                    <td rowspan="2">Target Output</td>
                                                                    <td rowspan="2">Actual
                                                                        Accomplishment</td>
                                                                    <td colspan="4">Rating</td>
                                                                    <td rowspan="2">Remarks</td>
                                                                    <td rowspan="2">Actions</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>E</td>
                                                                    <td>Q</td>
                                                                    <td>T</td>
                                                                    <td>A</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="white-space: nowrap;">
                                                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditTargetOutputModal" wire:click="selectIpcr('target_output', {{$target->id}}, 'edit')">Edit</a>
                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteTargetOutputModal" wire:click="selectIpcr('target_output', {{$target->id}})">Delete</a>
                                                                        </div>
                                                                        
                                                                            {{ $target->pivot->target_output }}
                                                                        
                                                                    </td>
                        
                                                                    {{-- <td class="text-center">
                                                                        <button type="button" class="ms-md-auto btn icon btn-primary" data-bs-toggle="modal"
                                                                            data-bs-target="#AddTargetOutputModal" title="Add Target Output">
                                                                            <i class="bi bi-plus"></i>
                                                                        </button>
                                                                    </td> --}}
                        
                                                                    @forelse ($target->ratings as $rating)
                                                                        @if ($rating->user_id == auth()->user()->id) 
                                                                            <td>{{ $rating->accomplishment }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($rating->efficiency)
                                                                                    {{ $rating->efficiency }}
                                                                                @else
                                                                                    NR
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($rating->quality)
                                                                                    {{ $rating->quality }}
                                                                                @else
                                                                                    NR
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($rating->timeliness)
                                                                                    {{ $rating->timeliness }}
                                                                                @else
                                                                                    NR
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $rating->average }}
                                                                            </td>
                                                                            <td>{{ $rating->remarks }}
                                                                            </td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="btn icon btn-success"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditRatingModal"
                                                                                    title="Edit Rating">
                                                                                    <i class="bi bi-pencil-square"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn icon btn-danger"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#DeleteModal"
                                                                                    title="Delete Rating">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                            @break
                                                                        @elseif ($loop->last)
                                                                            <td colspan="6"></td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="btn icon btn-primary"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#AddRatingModal"
                                                                                    title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                                    <i class="bi bi-plus"></i>
                                                                                </button>
                                                                            </td>
                                                                        @endif
                                                                    @empty
                                                                        <td colspan="6"></td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn icon btn-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddRatingModal"
                                                                                title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                                <i class="bi bi-plus"></i>
                                                                            </button>
                                                                        </td>=
                                                                    @endforelse
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @endforeach
                    </div>
                    <hr>
                @endforeach
                <div>
                    @foreach (auth()->user()->outputs()->where('funct_id', $funct->id)
                                ->where('type', 'ipcr')->where('user_type', 'staff')->get() as $output)
                        
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('output', {{$output->id}}, 'edit')">Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('output', {{$output->id}})">Delete</a>
                                    </div>
                                    {{ $output->code }} {{ $number++ }} - {{ $output->output }}
                                </h4>
                                <p class="text-subtitle text-muted"></p>
                            </div>

                            @forelse (auth()->user()->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                                
                                <div class="card-body">
                                    <h6>
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}}, 'edit')">Edit</a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}})">Delete</a>
                                        </div>
                                        {{ $suboutput->suboutput }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="accordion accordion-flush"
                                        id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                        <div class="d-sm-flex">
                                            @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                                <span class="my-auto">
                                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$target->id}})">Delete</a>
                                                    </div>
                                                </span>
                                                <div wire:ignore.self
                                                    class="accordion-button collapsed gap-2"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                    aria-expanded="true"
                                                    aria-controls="{{ 'target' }}{{ $target->id }}"
                                                    role="button">
                                                    <span class="my-auto">
                                                        <i class="bi bi-check2"></i>
                                                    </span>
                                                    {{ $target->target }}
                                                </div>  
                                            @endforeach
                                        </div>

                                        @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)

                                            <div wire:ignore.self
                                                id="{{ 'target' }}{{ $target->id }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingOne"
                                                data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                                <div class="accordion-body table-responsive">
                                                    <table class="table table-lg text-center">
                                                        <thead>
                                                            <tr>
                                                                <td rowspan="2">Target Output</td>
                                                                <td rowspan="2">Actual
                                                                    Accomplishment</td>
                                                                <td colspan="4">Rating</td>
                                                                <td rowspan="2">Remarks</td>
                                                                <td rowspan="2">Actions</td>
                                                            </tr>
                                                            <tr>
                                                                <td>E</td>
                                                                <td>Q</td>
                                                                <td>T</td>
                                                                <td>A</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="white-space: nowrap;">
                                                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditTargetOutputModal"  wire:click="selectIpcr('target_output', {{$target->id}}, 'edit')">Edit</a>
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteTargetOutputModal"  wire:click="selectIpcr('target_output', {{$target->id}})">Delete</a>
                                                                    </div>
                                                                    
                                                                        {{ $target->pivot->target_output }}
                                                                    
                                                                </td>
                    
                                                                {{-- <td class="text-center">
                                                                    <button type="button" class="ms-md-auto btn icon btn-primary" data-bs-toggle="modal"
                                                                        data-bs-target="#AddTargetOutputModal" title="Add Target Output">
                                                                        <i class="bi bi-plus"></i>
                                                                    </button>
                                                                </td> --}}
                    
                                                                @forelse ($target->ratings as $rating)
                                                                    @if ($rating->user_id == auth()->user()->id) 
                                                                        <td>{{ $rating->accomplishment }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->efficiency)
                                                                                {{ $rating->efficiency }}
                                                                            @else
                                                                                NR
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->quality)
                                                                                {{ $rating->quality }}
                                                                            @else
                                                                                NR
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->timeliness)
                                                                                {{ $rating->timeliness }}
                                                                            @else
                                                                                NR
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $rating->average }}
                                                                        </td>
                                                                        <td>{{ $rating->remarks }}
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn icon btn-success"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditRatingModal"
                                                                                title="Edit Rating">
                                                                                <i class="bi bi-pencil-square"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn icon btn-danger"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#DeleteModal"
                                                                                title="Delete Rating">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                        @break
                                                                    @elseif ($loop->last)
                                                                        <td colspan="6"></td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn icon btn-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddRatingModal"
                                                                                title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                                <i class="bi bi-plus"></i>
                                                                            </button>
                                                                        </td>
                                                                    @endif
                                                                @empty
                                                                    <td colspan="6"></td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn icon btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#AddRatingModal"
                                                                            title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                            <i class="bi bi-plus"></i>
                                                                        </button>
                                                                    </td>
                                                                @endforelse
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="card-body">
                                    <div class="accordion accordion-flush"
                                        id="{{ 'output' }}{{ $output->id }}">
                                        <div class="d-sm-flex">
                                            @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)
                                                <span class="my-auto">
                                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$target->id}})">Delete</a>
                                                    </div>
                                                </span>
                                                <div wire:ignore.self
                                                    class="accordion-button collapsed gap-2"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                    aria-expanded="true"
                                                    aria-controls="{{ 'target' }}{{ $target->id }}"
                                                    role="button">
                                                    <span class="my-auto">
                                                        <i class="bi bi-check2"></i>
                                                    </span>
                                                    {{ $target->target }}
                                                </div>  
                                            @endforeach
                                        </div>

                                        @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)

                                            <div wire:ignore.self
                                                id="{{ 'target' }}{{ $target->id }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingOne"
                                                data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                                <div class="accordion-body table-responsive">
                                                    <table class="table table-lg text-center">
                                                        <thead>
                                                            <tr>
                                                                <td rowspan="2">Target Output</td>
                                                                <td rowspan="2">Actual
                                                                    Accomplishment</td>
                                                                <td colspan="4">Rating</td>
                                                                <td rowspan="2">Remarks</td>
                                                                <td rowspan="2">Actions</td>
                                                            </tr>
                                                            <tr>
                                                                <td>E</td>
                                                                <td>Q</td>
                                                                <td>T</td>
                                                                <td>A</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="white-space: nowrap;">
                                                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditIPCROSTModal" wire:click="selectIpcr('target_output', {{$target->id}}, 'edit')">Edit</a>
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target_output', {{$target->id}})">Delete</a>
                                                                    </div>
                                                                    
                                                                        {{ $target->pivot->target_output }}
                                                                    
                                                                </td>
                    
                                                                {{-- <td class="text-center">
                                                                    <button type="button" class="ms-md-auto btn icon btn-primary" data-bs-toggle="modal"
                                                                        data-bs-target="#AddTargetOutputModal" title="Add Target Output">
                                                                        <i class="bi bi-plus"></i>
                                                                    </button>
                                                                </td> --}}
                    
                                                                @forelse ($target->ratings as $rating)
                                                                    @if ($rating->user_id == auth()->user()->id) 
                                                                        <td>{{ $rating->accomplishment }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->efficiency)
                                                                                {{ $rating->efficiency }}
                                                                            @else
                                                                                NR
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->quality)
                                                                                {{ $rating->quality }}
                                                                            @else
                                                                                NR
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->timeliness)
                                                                                {{ $rating->timeliness }}
                                                                            @else
                                                                                NR
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $rating->average }}
                                                                        </td>
                                                                        <td>{{ $rating->remarks }}
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn icon btn-success"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditRatingModal"
                                                                                title="Edit Rating">
                                                                                <i class="bi bi-pencil-square"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn icon btn-danger"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#DeleteModal"
                                                                                title="Delete Rating">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                        @break
                                                                    @elseif ($loop->last)
                                                                        <td colspan="6"></td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn icon btn-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddRatingModal"
                                                                                title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                                <i class="bi bi-plus"></i>
                                                                            </button>
                                                                        </td>
                                                                    @endif
                                                                @empty
                                                                    <td colspan="6"></td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn icon btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#AddRatingModal"
                                                                            title="Add Rating" {{ isset($approvalStandard) ? "" : "disabled" }}>
                                                                            <i class="bi bi-plus"></i>
                                                                        </button>
                                                                    </td>
                                                                @endforelse
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>

    {{ $functs->links('components.pagination') }}


    @php
        $subFuncts = auth()->user()->sub_functs;
        $currentPage = $functs->currentPage();
    @endphp
    <x-modals :selected="$selected" :currentPage="$currentPage" :duration="$duration" :subFuncts="$subFuncts" />
</div>