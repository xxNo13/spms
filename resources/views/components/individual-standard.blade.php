<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $user->name }} - STANDARD - {{ strtoupper($user_type) }}</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('for.approval') }}">For Approval</a></li>
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
            @if ($approval->review_id == auth()->user()->id && $approval->approve_id == auth()->user()->id)
                @if ($approval->review_status == 1 && $approval->approve_status != 1)
                    <div class="hstack mb-2">
                        <div class="ms-auto hstack gap-3">
                            <button type="button" class="btn icon btn-info"
                                wire:click="approved({{ $approval->id }}, 'Approved')">
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
                @elseif ($approval->review_status != 1)
                    <div class="hstack mb-2">
                        <div class="ms-auto hstack gap-3">
                            <button type="button" class="btn icon btn-info"
                                wire:click="approved({{ $approval->id }}, 'Reviewed')">
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
            @elseif ($approval->review_id == auth()->user()->id && $approval->review_status != 1)
                <div class="hstack mb-2">
                    <div class="ms-auto hstack gap-3">
                        <button type="button" class="btn icon btn-info"
                            wire:click="approved({{ $approval->id }}, 'Reviewed')">
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
            @elseif ($approval->approve_id == auth()->user()->id && $approval->approve_status != 1)
                <div class="hstack mb-2">
                    <div class="ms-auto hstack gap-3">
                        <button type="button" class="btn icon btn-info"
                            wire:click="approved({{ $approval->id }}, 'Approved')">
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
            <div class="hstack mb-3">
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
                            @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                {{ $sub_percentage->value }}
                            @endif
                        </h5>
                        @foreach ($user->outputs()->where('sub_funct_id', $sub_funct->id)->get() as $output)
                            @if ($output->type == $type &&
                                $output->duration_id == $duration->id &&
                                $output->user_type == $user_type)
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ $output->code }} {{ $output->output }}</h4>
                                        <p class="text-subtitle text-muted"></p>
                                    </div>
                                    @forelse ($user->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                                        <div class="card-body">
                                            <h6>{{ $suboutput->suboutput }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="accordion accordion-flush"
                                                id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                                <div class="d-sm-flex">
                                                    @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            @foreach ($target->standards as $standard)
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

                                                @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                                    <div wire:ignore.self
                                                        id="{{ 'target' }}{{ $target->id }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingOne"
                                                        data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                                        <div class="accordion-body table-responsive">
                                                            <table class="table table-lg text-center">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="6">Rating</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">E</td>
                                                                        <td colspan="2">Q</td>
                                                                        <td colspan="2">T</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($target->standards as $standard)
                                                                        @if ($standard->user_id == $user->id) 
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>{{ $target->standard->eff_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $target->standard->qua_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $target->standard->time_5 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>{{ $target->standard->eff_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $target->standard->qua_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $target->standard->time_4 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>{{ $target->standard->eff_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $target->standard->qua_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $target->standard->time_3 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>{{ $target->standard->eff_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $target->standard->qua_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $target->standard->time_2 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>{{ $target->standard->eff_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $target->standard->qua_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $target->standard->time_1 }}
                                                                                </td>
                                                                            </tr>
                                                                            @break
                                                                        @endif
                                                                    @endforeach
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
                                                    @foreach ($user->targets()->where('output_id', $output->id)->get() as $target)
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            @foreach ($target->standards as $standard)
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

                                                @foreach ($user->targets()->where('output_id', $output->id)->get() as $target)
                                                    <div wire:ignore.self
                                                        id="{{ 'target' }}{{ $target->id }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingOne"
                                                        data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                                        <div class="accordion-body table-responsive">
                                                            <table class="table table-lg text-center">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="6">Rating</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">E</td>
                                                                        <td colspan="2">Q</td>
                                                                        <td colspan="2">T</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($target->standards as $standard)
                                                                        @if ($standard->user_id == $user->id) 
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>{{ $target->standard->eff_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $target->standard->qua_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $target->standard->time_5 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>{{ $target->standard->eff_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $target->standard->qua_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $target->standard->time_4 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>{{ $target->standard->eff_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $target->standard->qua_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $target->standard->time_3 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>{{ $target->standard->eff_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $target->standard->qua_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $target->standard->time_2 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>{{ $target->standard->eff_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $target->standard->qua_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $target->standard->time_1 }}
                                                                                </td>
                                                                            </tr>
                                                                            @break
                                                                        @endif
                                                                    @endforeach
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
                @if ($output->type == $type && $output->duration_id == $duration->id && $output->user_type == $user_type)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $output->code }} {{ $output->output }}</h4>
                            <p class="text-subtitle text-muted"></p>
                        </div>
                        @forelse ($user->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                            <div class="card-body">
                                <h6>{{ $suboutput->suboutput }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="accordion accordion-flush"
                                    id="{{ 'output' }}{{ $output->id }}">
                                    <div class="d-sm-flex">
                                        @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                            <div wire:ignore.self
                                                class="accordion-button collapsed gap-2"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                aria-expanded="true"
                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                role="button">
                                                @foreach ($target->standards as $standard)
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

                                    @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                        <div wire:ignore.self
                                            id="{{ 'target' }}{{ $target->id }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne"
                                            data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                            <div class="accordion-body table-responsive">
                                                <table class="table table-lg text-center">
                                                    <thead>
                                                        <tr>
                                                            <td colspan="6">Rating</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">E</td>
                                                            <td colspan="2">Q</td>
                                                            <td colspan="2">T</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($target->standards as $standard)
                                                            @if ($standard->user_id == $user->id) 
                                                                <tr>
                                                                    <td>5</td>
                                                                    <td>{{ $target->standard->eff_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $target->standard->qua_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $target->standard->time_5 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>4</td>
                                                                    <td>{{ $target->standard->eff_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $target->standard->qua_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $target->standard->time_4 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>{{ $target->standard->eff_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $target->standard->qua_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $target->standard->time_3 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>{{ $target->standard->eff_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $target->standard->qua_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $target->standard->time_2 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>{{ $target->standard->eff_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $target->standard->qua_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $target->standard->time_1 }}
                                                                    </td>
                                                                </tr>
                                                                @break
                                                            @endif
                                                        @endforeach
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
                                        @foreach ($user->targets()->where('output_id', $output->id)->get() as $target)
                                            <div wire:ignore.self
                                                class="accordion-button collapsed gap-2"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                aria-expanded="true"
                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                role="button">
                                                @foreach ($target->standards as $standard)
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

                                    @foreach ($user->targets()->where('output_id', $output->id)->get() as $target)
                                        <div wire:ignore.self
                                            id="{{ 'target' }}{{ $target->id }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne"
                                            data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                            <div class="accordion-body table-responsive">
                                                <table class="table table-lg text-center">
                                                    <thead>
                                                        <tr>
                                                            <td colspan="6">Rating</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">E</td>
                                                            <td colspan="2">Q</td>
                                                            <td colspan="2">T</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($target->standards as $standard)
                                                            @if ($standard->user_id == $user->id) 
                                                                <tr>
                                                                    <td>5</td>
                                                                    <td>{{ $target->standard->eff_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $target->standard->qua_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $target->standard->time_5 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>4</td>
                                                                    <td>{{ $target->standard->eff_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $target->standard->qua_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $target->standard->time_4 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>{{ $target->standard->eff_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $target->standard->qua_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $target->standard->time_3 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>{{ $target->standard->eff_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $target->standard->qua_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $target->standard->time_2 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>{{ $target->standard->eff_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $target->standard->qua_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $target->standard->time_1 }}
                                                                    </td>
                                                                </tr>
                                                                @break
                                                            @endif
                                                        @endforeach
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
