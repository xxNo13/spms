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
                        <li class="breadcrumb-item active" aria-current="page">OPCR</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        @foreach ($functs as $funct)
            @php
                $number = 1;
            @endphp
            <div class="">
                <div class="hstack mb-3 gap-2">
                    <h4>
                        {{ $funct->funct }}
                        @if (isset($percentage))
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

                        @if ($duration && $duration->end_date >= date('Y-m-d'))
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
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#AddOSTModal" title="Add Output/Suboutput/Target">
                                Add OST
                            </button>
                        @endif
                    </div>
                </div>
                @if ($duration)
                    @foreach ($funct->sub_functs()->where('funct_id', $funct->id)->where('user_type', 'office')->where('type', 'opcr')->where('duration_id', $duration->id)->get() as $sub_funct)
                        <div>
                            <h5>
                                @if (($duration && $duration->end_date >= date('Y-m-d')) && $sub_funct->added_by == auth()->user()->id)
                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('sub_funct', {{$sub_funct->id}}, 'edit')">Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal"  wire:click="selectIpcr('sub_funct', {{$sub_funct->id}})">Delete</a>
                                    </div>
                                @endif
                                {{ $sub_funct->sub_funct }}
                                @if ($sub_percentage = $sub_percentages->where('sub_funct_id', $sub_funct->id)->first())
                                    {{ $sub_percentage->value }}%
                                @endif
                            </h5>

                            @foreach ($sub_funct->outputs()->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $output)
                                
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            @if (($duration && $duration->end_date >= date('Y-m-d')) && $output->added_by == auth()->user()->id)
                                                <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('output', {{$output->id}}, 'edit')">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('output', {{$output->id}})">Delete</a>
                                                </div>
                                            @endif
                                            {{ $output->code }} {{ $number++ }} - {{ $output->output }}
                                        </h4>
                                        <p class="text-subtitle text-muted"></p>
                                    </div>

                                    @forelse ($output->suboutputs as $suboutput)
                                        <div class="card-body">
                                            <h6>
                                                @if (($duration && $duration->end_date >= date('Y-m-d')) && $suboutput->added_by == auth()->user()->id)
                                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}}, 'edit')">Edit</a>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}})">Delete</a>
                                                    </div>
                                                @endif
                                                {{ $suboutput->suboutput }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="accordion accordion-flush"
                                                id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                                <div class="row">
                                                    @foreach ($suboutput->targets as $target)
                                                        <div class="col-12 col-sm-4 d-flex">
                                                            <span class="my-auto">
                                                                @if (($duration && $duration->end_date >= date('Y-m-d')) && $target->added_by == auth()->user()->id)
                                                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$target->id}})">Delete</a>
                                                                    </div>
                                                                @endif
                                                            </span>
                                                            <div wire:ignore.self
                                                                class="accordion-button collapsed gap-2"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                                aria-expanded="true"
                                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                                role="button">
                                                                {{ $target->target }}
                                                            </div> 
                                                        </div> 
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="card-body">
                                            <div class="accordion accordion-flush"
                                                id="{{ 'output' }}{{ $output->id }}">
                                                <div class="row">
                                                    @foreach ($output->targets as $target)
                                                        <div class="col-12 col-sm-4 d-flex">
                                                            <span class="my-auto">
                                                                @if (($duration && $duration->end_date >= date('Y-m-d')) && $target->added_by == auth()->user()->id)
                                                                    <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$target->id}})">Delete</a>
                                                                    </div>
                                                                @endif
                                                            </span>
                                                            <div wire:ignore.self
                                                                class="accordion-button collapsed gap-2"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                                aria-expanded="true"
                                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                                role="button">
                                                                {{ $target->target }}
                                                            </div> 
                                                        </div> 
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endforeach
                    <div>
                        @foreach ($funct->outputs()->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $output)
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        @if (($duration && $duration->end_date >= date('Y-m-d')) && $output->added_by == auth()->user()->id)
                                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('output', {{$output->id}}, 'edit')">Edit</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('output', {{$output->id}})">Delete</a>
                                            </div>
                                        @endif
                                        {{ $output->code }} {{ $number++ }} - {{ $output->output }}
                                    </h4>
                                    <p class="text-subtitle text-muted"></p>
                                </div>

                                @forelse ($output->suboutputs as $suboutput)
                                    
                                    <div class="card-body">
                                        <h6>
                                            @if (($duration && $duration->end_date >= date('Y-m-d')) && $suboutput->added_by == auth()->user()->id)
                                                <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}}, 'edit')">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('suboutput', {{$suboutput->id}})">Delete</a>
                                                </div>
                                            @endif
                                            {{ $suboutput->suboutput }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion accordion-flush"
                                            id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                            <div class="row">
                                                @foreach ($suboutput->targets as $target)
                                                    <div class="col-12 col-sm-4 d-flex">
                                                        <span class="my-auto">
                                                            @if (($duration && $duration->end_date >= date('Y-m-d')) && $target->added_by == auth()->user()->id)
                                                                <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$target->id}})">Delete</a>
                                                                </div>
                                                            @endif
                                                        </span>
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            {{ $target->target }}
                                                        </div> 
                                                    </div> 
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="card-body">
                                        <div class="accordion accordion-flush"
                                            id="{{ 'output' }}{{ $output->id }}">
                                            <div class="row">
                                                @foreach ($output->targets as $target)
                                                    <div class="col-12 col-sm-4 d-flex">
                                                        <span class="my-auto">
                                                            @if (($duration && $duration->end_date >= date('Y-m-d')) && $target->added_by == auth()->user()->id)
                                                                <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#EditOSTModal" wire:click="selectIpcr('target', {{$target->id}}, 'edit')">Edit</a>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" wire:click="selectIpcr('target', {{$target->id}})">Delete</a>
                                                                </div>
                                                            @endif
                                                        </span>
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            {{ $target->target }}
                                                        </div>  
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </section>

    {{ $functs->links('components.pagination') }}


    @php
        $currentPage = $functs->currentPage();
        $userType = 'listing';
    @endphp
    @if ($duration)
        <x-modals :selected="$selected" :userType="$userType" :currentPage="$currentPage" :duration="$duration" :outputs="$outputs" :subFuncts="$subFuncts" />
    @endif
</div>