<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $user->name }} - OPCR</h3>
            </div>
            <div class="col-12 col-md-6 order-md-3 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a
                                href="{{ route('dashboard') }}">Dashboard</a></li>
                        @if ($url == 'officemates')
                            <li class="breadcrumb-item active" aria-current="page"><a
                                    href="{{ route('subordinates') }}">Officemates</a></li>
                        @elseif ($url == 'for-approval')
                            <li class="breadcrumb-item active" aria-current="page"><a
                                    href="{{ route('for.approval') }}">For Approval</a></li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        @if ($url == 'for-approval')
            <div class="my-5">
                @php
                    $progress = 0;
                @endphp
                <div class="hstack text-center text-nowrap">
                    <span class="mx-3 w-50">
                        @if ($approval->review_status == 1)
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            @php
                                $progress += 50;
                            @endphp
                        @else
                            <i class="bi bi-x-circle text-danger fs-1"></i>
                        @endif
                        <p>Reviewed</p>
                    </span>
                    <span class="mx-3 w-50">
                        @if ($approval->approve_status == 1)
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            @php
                                $progress += 50;
                            @endphp
                        @else
                            <i class="bi bi-x-circle  text-danger fs-1"></i>
                        @endif
                        <p>Approved</p>
                    </span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated w-{{ $progress }}" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            @if ($approval->review_id == auth()->user()->id || $approval->review_status == 1)
                <div class="hstack mb-2">
                    <div class="ms-auto hstack gap-3">
                        <button type="button" class="btn icon btn-info"
                            wire:click="approved({{ $approval->id }})">
                            <i class="bi bi-check"></i>
                            Approved
                        </button>
                        <button type="button" class="btn icon btn-danger"
                            wire:click="clickdeclined({{ $approval->id }})"  data-bs-toggle="modal" data-bs-target="#DeclineModal">
                            <i class="bi bi-x"></i>
                            Decline
                        </button>
                    </div>
                </div>
            @endif
        @endif

        @foreach ($functs as $funct)
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
            </div>
            @if ($funct->sub_functs)
                @foreach ($user->sub_functs()->where('funct_id', $funct->id)->get() as $sub_funct)
                    <div>
                        <h5>
                            {{ $sub_funct->sub_funct }}
                            @foreach ($percentage->supports as $support)
                                @if ($support->sub_funct_id == $sub_funct->id)
                                    {{ $support->percent }}%
                                @endif
                            @endforeach
                        </h5>
                        @foreach ($user->outputs()->where('sub_funct_id', $sub_funct->id)->get() as $output)
                            @if ($output->type == 'opcr' &&
                                $output->duration_id == $duration->id &&
                                $output->user_type == $userType)
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{ $output->code }} {{ $number++ }}
                                            {{ $output->output }}
                                        </h4>
                                        <p class="text-subtitle text-muted"></p>
                                    </div>
                                    @forelse ($output->suboutputs as $suboutput)
                                        <div class="card-body">
                                            <h6>
                                                {{ $suboutput->suboutput }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="accordion accordion-flush"
                                                id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                                <div class="d-sm-flex">
                                                    @foreach ($suboutput->targets as $target)
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            @foreach ($target->ratings as $rating)
                                                                @if ($rating->user_id == $user->id)
                                                                    <span class="my-auto">
                                                                        <i class="bi bi-check2"></i>
                                                                    </span>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                            {{ $target->target }}
                                                        </div>  
                                                    @endforeach
                                                </div>

                                                @foreach ($suboutput->targets as $target)
                                                    <div wire:ignore.self
                                                        id="{{ 'target' }}{{ $target->id }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingOne"
                                                        data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                                        <div class="accordion-body table-responsive">
                                                            <table class="table table-lg text-center">
                                                                <thead>
                                                                    <tr>
                                                                        <td rowspan="2">Alloted Budget</td>
                                                                        <td rowspan="2">Responsible Office/Person</td>
                                                                        <td rowspan="2">Actual Accomplishment</td>
                                                                        <td colspan="4">Rating</td>
                                                                        <td rowspan="2">Remarks</td>
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
                                                                        <td>{{ "₱ " . number_format($target->alloted_budget) }}</td>
                                                                        <td>{{ $target->responsible }}</td>
                                                                        
                                                                        @foreach ($target->ratings as $rating)
                                                                            @if ($rating->user_id == $user->id) 
                                                                                <tr>
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
                                                                                </tr>
                                                                                @break
                                                                            @endif
                                                                        @endforeach
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
                                                    @foreach ($output->targets as $target)
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            @foreach ($target->ratings as $rating)
                                                                @if ($rating->user_id == $user->id)
                                                                    <span class="my-auto">
                                                                        <i class="bi bi-check2"></i>
                                                                    </span>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                            {{ $target->target }}
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @foreach ($suboutput->targets as $target)
                                                    <div wire:ignore.self
                                                        id="{{ 'target' }}{{ $target->id }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingOne"
                                                        data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                                        <div class="accordion-body table-responsive">
                                                            <table class="table table-lg text-center">
                                                                <thead>
                                                                    <tr>
                                                                        <td rowspan="2">Alloted Budget</td>
                                                                        <td rowspan="2">Responsible Office/Person</td>
                                                                        <td rowspan="2">Actual Accomplishment</td>
                                                                        <td colspan="4">Rating</td>
                                                                        <td rowspan="2">Remarks</td>
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
                                                                        <td>{{ "₱ " . number_format($target->alloted_budget) }}</td>
                                                                        <td>{{ $target->responsible }}</td>
                                                                        
                                                                        @foreach ($target->ratings as $rating)
                                                                            @if ($rating->user_id == $user->id) 
                                                                                <tr>
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
                                                                                </tr>
                                                                                @break
                                                                            @endif
                                                                        @endforeach
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
                            @endif
                        @endforeach
                    </div>
                    <hr>
                @endforeach
            @endif
            @foreach ($user->outputs()->where('funct_id', $funct->id)->get() as $output)
                @if ($output->type == 'opcr' &&
                    $output->duration_id == $duration->id &&
                    $output->user_type == $userType)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{ $output->code }} {{ $number++ }} {{ $output->output }}
                            </h4>
                            <p class="text-subtitle text-muted"></p>
                        </div>
                        @forelse ($output->suboutputs as $suboutput)
                            <div class="card-body">
                                <h6>
                                    {{ $suboutput->suboutput }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="accordion accordion-flush"
                                    id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                    <div class="d-sm-flex">
                                        @foreach ($suboutput->targets as $target)
                                            <div wire:ignore.self class="accordion-button collapsed gap-2"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                aria-expanded="true"
                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                role="button">
                                                @foreach ($target->ratings as $rating)
                                                    @if ($rating->user_id == $user->id)
                                                        <span class="my-auto">
                                                            <i class="bi bi-check2"></i>
                                                        </span>
                                                        @break
                                                    @endif
                                                @endforeach
                                                {{ $target->target }}
                                            </div>
                                        @endforeach
                                    </div>

                                    @foreach ($suboutput->targets as $target)
                                        <div wire:ignore.self
                                            id="{{ 'target' }}{{ $target->id }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne"
                                            data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                            <div class="accordion-body table-responsive">
                                                <table class="table table-lg text-center">
                                                    <thead>
                                                        <tr>
                                                            <td rowspan="2">Alloted Budget</td>
                                                            <td rowspan="2">Responsible Office/Person</td>
                                                            <td rowspan="2">Actual Accomplishment</td>
                                                            <td colspan="4">Rating</td>
                                                            <td rowspan="2">Remarks</td>
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
                                                            <td>{{ "₱ " . number_format($target->alloted_budget) }}</td>
                                                            <td>{{ $target->responsible }}</td>
                                                            
                                                            @foreach ($target->ratings as $rating)
                                                                @if ($rating->user_id == $user->id) 
                                                                    <tr>
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
                                                                    </tr>
                                                                    @break
                                                                @endif
                                                            @endforeach
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
                                    id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                    <div class="d-sm-flex">
                                        @foreach ($suboutput->targets as $target)
                                            <div wire:ignore.self class="accordion-button collapsed gap-2"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                aria-expanded="true"
                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                role="button">
                                                @foreach ($target->ratings as $rating)
                                                    @if ($rating->user_id == $user->id)
                                                        <span class="my-auto">
                                                            <i class="bi bi-check2"></i>
                                                        </span>
                                                        @break
                                                    @endif
                                                @endforeach
                                                {{ $target->target }}
                                            </div>
                                        @endforeach
                                    </div>

                                    @foreach ($suboutput->targets as $target)
                                        <div wire:ignore.self
                                            id="{{ 'target' }}{{ $target->id }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne"
                                            data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                            <div class="accordion-body table-responsive">
                                                <table class="table table-lg text-center">
                                                    <thead>
                                                        <tr>
                                                            <td rowspan="2">Alloted Budget</td>
                                                            <td rowspan="2">Responsible Office/Person</td>
                                                            <td rowspan="2">Actual Accomplishment</td>
                                                            <td colspan="4">Rating</td>
                                                            <td rowspan="2">Remarks</td>
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
                                                            <td>{{ "₱ " . number_format($target->alloted_budget) }}</td>
                                                            <td>{{ $target->responsible }}</td>
                                                            
                                                            @foreach ($target->ratings as $rating)
                                                                @if ($rating->user_id == $user->id) 
                                                                    <tr>
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
                                                                    </tr>
                                                                    @break
                                                                @endif
                                                            @endforeach
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
                @endif
            @endforeach
        @endforeach
    </section>
    <x-modals />
    
</div>
