<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Standard</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a
                                href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Standard - Faculty</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
            
        {{-- Message for declining --}}
        <div wire:ignore class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11">
            @if ($review_user && $review_user['message'])
                <div id="reviewToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                    <div class="toast-header">
                        <strong class="me-auto">{{ $review_user['name'] }} Declining Message:</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <strong class="me-auto">{{ $review_user['message'] }}</strong>
                    </div>
                </div>
                @push ('script')
                    <script>
                        new bootstrap.Toast(document.getElementById('reviewToast')).show();
                    </script>
                @endpush
            @endif
            @if ($approve_user && $approve_user['message']) 
                <div id="approveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                    <div class="toast-header">
                        <strong class="me-auto">{{ $approve_user['name'] }} Declining Message:</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <strong class="me-auto">{{ $approve_user['message'] }}</strong>
                    </div>
                </div>
                @push ('script')
                    <script>
                        new bootstrap.Toast(document.getElementById('approveToast')).show();
                    </script>
                @endpush
            @endif
        </div>

        @foreach ($functs as $funct)
            <div class="hstack mb-3">
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
                    @if (($duration && $duration->end_date >= date('Y-m-d')) && (!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)))
                        <button type="button" class="btn btn-outline-info" title="Submit Standard" wire:click="submit('approval')">
                            Submit
                        </button>
                    @endif

                    @if ($duration && $approval && $approval->approve_status == 1)
                        <a href="{{ route('print.standard.opcr', ['id' => auth()->user()->id]) }}" target="_blank" class="btn icon btn-primary" title="Print Standard">
                            <i class="bi bi-printer"></i>
                        </a>
                    @endif
                </div>
            </div>
            @if ($duration)
                @foreach (auth()->user()->sub_functs()->where('funct_id', $funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $sub_funct)
                    <div>
                        <h5>
                            {{ $sub_funct->sub_funct }}
                            @if ($sub_percentage = $sub_percentages->where('sub_funct_id', $sub_funct->id)->first())
                                {{ $sub_percentage->value }}%
                            @endif
                        </h5>
                        @foreach (auth()->user()->outputs()->where('sub_funct_id', $sub_funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $output)
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ $output->code }} {{ $output->output }}</h4>
                                        <p class="text-subtitle text-muted"></p>
                                    </div>
                                    @forelse (auth()->user()->suboutputs()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $suboutput)
                                        <div class="card-body">
                                            <h6>{{ $suboutput->suboutput }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="accordion accordion-flush"
                                                id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                                <div class="row">
                                                    @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                                        <div class="col-12 col-sm-4">
                                                            <div wire:ignore.self
                                                                class="accordion-button collapsed gap-2"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                                aria-expanded="true"
                                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                                role="button">
                                                                @foreach ($target->standards as $standard)
                                                                    @if ($standard->user_id == auth()->user()->id)
                                                                        <span class="my-auto">
                                                                            <i class="bi bi-check2"></i>
                                                                        </span>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                                {{ $target->target }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
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
                                                                        <td rowspan="2">Action</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">E</td>
                                                                        <td colspan="2">Q</td>
                                                                        <td colspan="2">T</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse ($target->standards as $standard)
                                                                        @if ($standard->user_id == auth()->user()->id) 
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>{{ $standard->eff_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $standard->qua_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $standard->time_5 }}
                                                                                </td>
                                                                                <td rowspan="5">
                                                                                    @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                                        <button type="button"
                                                                                            class="btn icon btn-success"
                                                                                            wire:click="clicked('{{ 'edit' }}', {{ $standard->id }})"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#EditStandardModal"
                                                                                            title="Edit Standard">
                                                                                            <i
                                                                                                class="bi bi-pencil-square"></i>
                                                                                        </button>
                                                                                        <button type="button"
                                                                                            class="btn icon btn-danger"
                                                                                            wire:click="clicked('{{ 'delete' }}', {{ $standard->id }})"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#DeleteModal"
                                                                                            title="Delete Standard">
                                                                                            <i
                                                                                                class="bi bi-trash"></i>
                                                                                        </button>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>{{ $standard->eff_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $standard->qua_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $standard->time_4 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>{{ $standard->eff_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $standard->qua_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $standard->time_3 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>{{ $standard->eff_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $standard->qua_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $standard->time_2 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>{{ $standard->eff_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $standard->qua_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $standard->time_1 }}
                                                                                </td>
                                                                            </tr>
                                                                            @break
                                                                        @elseif ($loop->last)
                                                                            <tr>
                                                                                <td colspan="6"></td>
                                                                                <td>
                                                                                    @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                                        <button type="button"
                                                                                            class="btn icon btn-primary"
                                                                                            wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#AddStandardModal"
                                                                                            title="Add Standard">
                                                                                            <i
                                                                                                class="bi bi-plus"></i>
                                                                                        </button>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="6"></td>
                                                                            <td>
                                                                                @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                                    <button type="button"
                                                                                        class="btn icon btn-primary"
                                                                                        wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#AddStandardModal"
                                                                                        title="Add Standard">
                                                                                        <i
                                                                                            class="bi bi-plus"></i>
                                                                                    </button>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforelse
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
                                                <div class="row">
                                                    @foreach (auth()->user()->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                                        <div class="col-12 col-sm-4">
                                                            <div wire:ignore.self
                                                                class="accordion-button collapsed gap-2"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                                aria-expanded="true"
                                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                                role="button">
                                                                @foreach ($target->standards as $standard)
                                                                    @if ($standard->user_id == auth()->user()->id)
                                                                        <span class="my-auto">
                                                                            <i class="bi bi-check2"></i>
                                                                        </span>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                                {{ $target->target }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @foreach (auth()->user()->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
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
                                                                        <td rowspan="2">Action</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">E</td>
                                                                        <td colspan="2">Q</td>
                                                                        <td colspan="2">T</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse ($target->standards as $standard)
                                                                        @if ($standard->user_id == auth()->user()->id) 
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>{{ $standard->eff_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $standard->qua_5 }}
                                                                                </td>
                                                                                <td>5</td>
                                                                                <td>{{ $standard->time_5 }}
                                                                                </td>
                                                                                <td rowspan="5">
                                                                                    @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                                        <button type="button"
                                                                                            class="btn icon btn-success"
                                                                                            wire:click="clicked('{{ 'edit' }}', {{ $standard->id }})"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#EditStandardModal"
                                                                                            title="Edit Standard">
                                                                                            <i
                                                                                                class="bi bi-pencil-square"></i>
                                                                                        </button>
                                                                                        <button type="button"
                                                                                            class="btn icon btn-danger"
                                                                                            wire:click="clicked('{{ 'delete' }}', {{ $standard->id }})"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#DeleteModal"
                                                                                            title="Delete Standard">
                                                                                            <i
                                                                                                class="bi bi-trash"></i>
                                                                                        </button>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>{{ $standard->eff_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $standard->qua_4 }}
                                                                                </td>
                                                                                <td>4</td>
                                                                                <td>{{ $standard->time_4 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>{{ $standard->eff_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $standard->qua_3 }}
                                                                                </td>
                                                                                <td>3</td>
                                                                                <td>{{ $standard->time_3 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>{{ $standard->eff_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $standard->qua_2 }}
                                                                                </td>
                                                                                <td>2</td>
                                                                                <td>{{ $standard->time_2 }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>{{ $standard->eff_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $standard->qua_1 }}
                                                                                </td>
                                                                                <td>1</td>
                                                                                <td>{{ $standard->time_1 }}
                                                                                </td>
                                                                            </tr>
                                                                            @break
                                                                        @elseif ($loop->last)
                                                                            <tr>
                                                                                <td colspan="6"></td>
                                                                                <td>
                                                                                    @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                                        <button type="button"
                                                                                            class="btn icon btn-primary"
                                                                                            wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#AddStandardModal"
                                                                                            title="Add Standard">
                                                                                            <i
                                                                                                class="bi bi-plus"></i>
                                                                                        </button>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="6"></td>
                                                                            <td>
                                                                                @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                                    <button type="button"
                                                                                        class="btn icon btn-primary"
                                                                                        wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#AddStandardModal"
                                                                                        title="Add Standard">
                                                                                        <i
                                                                                            class="bi bi-plus"></i>
                                                                                    </button>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforelse
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
                @foreach (auth()->user()->outputs()->where('funct_id', $funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $output)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $output->code }} {{ $output->output }}</h4>
                            <p class="text-subtitle text-muted"></p>
                        </div>
                        @forelse (auth()->user()->suboutputs()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $suboutput)
                            <div class="card-body">
                                <h6>{{ $suboutput->suboutput }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="accordion accordion-flush"
                                    id="{{ 'output' }}{{ $output->id }}">
                                    <div class="row">
                                        @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                            <div class="col-12 col-sm-4">
                                                <div wire:ignore.self
                                                    class="accordion-button collapsed gap-2"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                    aria-expanded="true"
                                                    aria-controls="{{ 'target' }}{{ $target->id }}"
                                                    role="button">
                                                    @foreach ($target->standards as $standard)
                                                        @if ($standard->user_id == auth()->user()->id)
                                                            <span class="my-auto">
                                                                <i class="bi bi-check2"></i>
                                                            </span>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    {{ $target->target }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
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
                                                            <td rowspan="2">Action</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">E</td>
                                                            <td colspan="2">Q</td>
                                                            <td colspan="2">T</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($target->standards as $standard)
                                                            @if ($standard->user_id == auth()->user()->id) 
                                                                <tr>
                                                                    <td>5</td>
                                                                    <td>{{ $standard->eff_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $standard->qua_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $standard->time_5 }}
                                                                    </td>
                                                                    <td rowspan="5">
                                                                        @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                            <button type="button"
                                                                                class="btn icon btn-success"
                                                                                wire:click="clicked('{{ 'edit' }}', {{ $standard->id }})"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditStandardModal"
                                                                                title="Edit Standard">
                                                                                <i
                                                                                    class="bi bi-pencil-square"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn icon btn-danger"
                                                                                wire:click="clicked('{{ 'delete' }}', {{ $standard->id }})"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#DeleteModal"
                                                                                title="Delete Standard">
                                                                                <i
                                                                                    class="bi bi-trash"></i>
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>4</td>
                                                                    <td>{{ $standard->eff_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $standard->qua_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $standard->time_4 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>{{ $standard->eff_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $standard->qua_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $standard->time_3 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>{{ $standard->eff_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $standard->qua_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $standard->time_2 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>{{ $standard->eff_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $standard->qua_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $standard->time_1 }}
                                                                    </td>
                                                                </tr>
                                                                @break
                                                            @elseif ($loop->last)
                                                                <tr>
                                                                    <td colspan="6"></td>
                                                                    <td>
                                                                        @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                            <button type="button"
                                                                                class="btn icon btn-primary"
                                                                                wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddStandardModal"
                                                                                title="Add Standard">
                                                                                <i
                                                                                    class="bi bi-plus"></i>
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @empty
                                                            <tr>
                                                                <td colspan="6"></td>
                                                                <td>
                                                                    @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                        <button type="button"
                                                                            class="btn icon btn-primary"
                                                                            wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#AddStandardModal"
                                                                            title="Add Standard">
                                                                            <i
                                                                                class="bi bi-plus"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforelse
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
                                    <div class="row">
                                        @foreach (auth()->user()->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                            <div class="col-12 col-sm-4">
                                                <div wire:ignore.self
                                                    class="accordion-button collapsed gap-2"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                    aria-expanded="true"
                                                    aria-controls="{{ 'target' }}{{ $target->id }}"
                                                    role="button">
                                                    @foreach ($target->standards as $standard)
                                                        @if ($standard->user_id == auth()->user()->id)
                                                            <span class="my-auto">
                                                                <i class="bi bi-check2"></i>
                                                            </span>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    {{ $target->target }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @foreach (auth()->user()->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
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
                                                            <td rowspan="2">Action</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">E</td>
                                                            <td colspan="2">Q</td>
                                                            <td colspan="2">T</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($target->standards as $standard)
                                                            @if ($standard->user_id == auth()->user()->id) 
                                                                <tr>
                                                                    <td>5</td>
                                                                    <td>{{ $standard->eff_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $standard->qua_5 }}
                                                                    </td>
                                                                    <td>5</td>
                                                                    <td>{{ $standard->time_5 }}
                                                                    </td>
                                                                    <td rowspan="5">
                                                                        @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                            <button type="button"
                                                                                class="btn icon btn-success"
                                                                                wire:click="clicked('{{ 'edit' }}', {{ $standard->id }})"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditStandardModal"
                                                                                title="Edit Standard">
                                                                                <i
                                                                                    class="bi bi-pencil-square"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn icon btn-danger"
                                                                                wire:click="clicked('{{ 'delete' }}', {{ $standard->id }})"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#DeleteModal"
                                                                                title="Delete Standard">
                                                                                <i
                                                                                    class="bi bi-trash"></i>
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>4</td>
                                                                    <td>{{ $standard->eff_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $standard->qua_4 }}
                                                                    </td>
                                                                    <td>4</td>
                                                                    <td>{{ $standard->time_4 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>{{ $standard->eff_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $standard->qua_3 }}
                                                                    </td>
                                                                    <td>3</td>
                                                                    <td>{{ $standard->time_3 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>{{ $standard->eff_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $standard->qua_2 }}
                                                                    </td>
                                                                    <td>2</td>
                                                                    <td>{{ $standard->time_2 }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>{{ $standard->eff_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $standard->qua_1 }}
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>{{ $standard->time_1 }}
                                                                    </td>
                                                                </tr>
                                                                @break
                                                            @elseif ($loop->last)
                                                                <tr>
                                                                    <td colspan="6"></td>
                                                                    <td>
                                                                        @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                            <button type="button"
                                                                                class="btn icon btn-primary"
                                                                                wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddStandardModal"
                                                                                title="Add Standard">
                                                                                <i
                                                                                    class="bi bi-plus"></i>
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @empty
                                                            <tr>
                                                                <td colspan="6"></td>
                                                                <td>
                                                                    @if ((!$approval || (isset($approval->approve_status) && $approval->approve_status != 1)) && ($duration && $duration->end_date >= date('Y-m-d')))
                                                                        <button type="button"
                                                                            class="btn icon btn-primary"
                                                                            wire:click="clicked('{{ 'add' }}', {{ $target->id }})"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#AddStandardModal"
                                                                            title="Add Standard">
                                                                            <i
                                                                                class="bi bi-plus"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforelse
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
            @endif
        @endforeach
    </section>


    {{ $functs->links('components.pagination') }}
    <x-modals :standardValue="$standardValue"  />
</div>
