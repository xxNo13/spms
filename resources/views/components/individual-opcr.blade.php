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
                                    href="{{ route('subordinates') }}">Subordinates</a></li>
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
        {{-- Message for declining --}}
        <div wire:ignore.self class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11">
            @if (isset($prevApproval) && !isset($approval->approve_status))
                @foreach ($prevApproval->reviewers as $reviewer)
                    @if ($reviewer->pivot->review_message)
                        <div id="reviewToast{{ $reviewer->id }}" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                            <div class="toast-header">
                                <strong class="me-auto">{{ $reviewer->name }}'s <br/> Comment/s in Pervious Submission:</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                <strong class="me-auto"><?php echo nl2br($reviewer->pivot->review_message) ?></strong>
                            </div>
                        </div>
                        @push ('script')
                            <script>
                                var data = "reviewToast" + "<?php echo $reviewer->id ?>";
                                new bootstrap.Toast(document.getElementById(data)).show();
                            </script>
                        @endpush
                    @endif
                @endforeach
                @if (isset($prevApproval->approve_message) && $prevApproval->approve_message != '') 
                    <div id="approveToast" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                        <div class="toast-header">
                            <strong class="me-auto">{{ $prevApprover->name }}'s <br/> Comment/s in Pervious Submission:</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <strong class="me-auto"><?php echo nl2br($prevApproval->approve_message) ?></strong>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        @if ($url == 'for-approval')
            <div class="my-5">
                @php
                    $progress = 0;
                @endphp
                <div class="hstack text-center text-nowrap">
                    <span class="mx-3 w-50">
                        @php
                            $prog = 100 / (count($approval->reviewers) + 1);
                            $rev = 0;
                        @endphp

                        @foreach ($approval->reviewers as $reviewer) 
                            @if ($reviewer->pivot->review_status == 1) 
                                @php
                                    $rev++;
                                    $progress += $prog;
                                @endphp
                            @endif
                        @endforeach

                        @if ($rev == count($approval->reviewers))
                            <i class="bi bi-check-circle text-success fs-1"></i>
                        @else
                            <i class="bi bi-x-circle text-danger fs-1"></i>
                        @endif
                        <p>Reviewed</p>
                    </span>
                    <span class="mx-3 w-50">
                        @if ($approval->approve_status == 1)
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            @php
                                $progress += $prog;
                            @endphp
                        @else
                            <i class="bi bi-x-circle text-danger fs-1"></i>
                        @endif
                        <p>Approved</p>
                    </span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: {{ $progress }}%;" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            @if (in_array($approval->id, auth()->user()->user_approvals()->pluck('approval_id')->toArray()) && $approval->approve_id == auth()->user()->id)
                @if ($approval->reviewers()->where('user_id', auth()->user()->id)->first()->pivot->review_status == 1 && $approval->approve_status != 1)
                    <div class="hstack mb-2 fixed-bottom mx-5 px-4">
                        <div class="ms-auto hstack gap-3 bg-secondary rounded p-3">
                            <button type="button" class="btn icon btn-info"
                                wire:click="approved({{ $approval->id }}, 'Approved', true)">
                                <i class="bi bi-check"></i>
                                Approved
                            </button>
                            @if ($approval->name == 'assess')
                                <button type="button" class="btn icon btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#PrintCommentModal">
                                    <i class="bi bi-x"></i>
                                    Add a Comment
                                </button>
                            @else 
                                <button type="button" class="btn icon btn-danger"
                                    wire:click="declined({{ $approval->id }})">
                                    <i class="bi bi-x"></i>
                                    Decline
                                </button>
                            @endif
                        </div>
                    </div>
                @elseif ($approval->reviewers()->where('user_id', auth()->user()->id)->first()->pivot->review_status != 1)
                    <div class="hstack mb-2 fixed-bottom mx-5 px-4">
                        <div class="ms-auto hstack gap-3 bg-secondary rounded p-3">
                            <button type="button" class="btn icon btn-info"
                                wire:click="approved({{ $approval->id }}, 'Reviewed', true)">
                                <i class="bi bi-check"></i>
                                Approved
                            </button>
                            @if ($approval->name == 'assess')
                                <button type="button" class="btn icon btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#PrintCommentModal">
                                    <i class="bi bi-x"></i>
                                    Add a Comment
                                </button>
                            @else 
                                <button type="button" class="btn icon btn-danger"
                                    wire:click="declined({{ $approval->id }})">
                                    <i class="bi bi-x"></i>
                                    Decline
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @elseif ($approval->reviewers()->where('user_id', auth()->user()->id)->first() && $approval->reviewers()->where('user_id', auth()->user()->id)->first()->pivot->review_status != 1)
                <div class="hstack mb-2 fixed-bottom mx-5 px-4">
                    <div class="ms-auto hstack gap-3 bg-secondary rounded p-3">
                        <button type="button" class="btn icon btn-info"
                            wire:click="approved({{ $approval->id }}, 'Reviewed', true)">
                            <i class="bi bi-check"></i>
                            Approved
                        </button>
                        @if ($approval->name == 'assess')
                            <button type="button" class="btn icon btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#PrintCommentModal">
                                <i class="bi bi-x"></i>
                                Add a Comment
                            </button>
                        @else 
                            <button type="button" class="btn icon btn-danger"
                                wire:click="declined({{ $approval->id }})">
                                <i class="bi bi-x"></i>
                                Decline
                            </button>
                        @endif
                    </div>
                </div>
            @elseif ($approval->approve_id == auth()->user()->id && $approval->approve_status != 1)
                @if ($rev == count($approval->reviewers))
                    <div class="hstack mb-2 fixed-bottom mx-5 px-4">
                        <div class="ms-auto hstack gap-3 bg-secondary rounded p-3">
                            <button type="button" class="btn icon btn-info"
                                wire:click="approved({{ $approval->id }}, 'Approved', true)">
                                <i class="bi bi-check"></i>
                                Approved
                            </button>
                            @if ($approval->name == 'assess')
                                <button type="button" class="btn icon btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#PrintCommentModal">
                                    <i class="bi bi-x"></i>
                                    Add a Comment
                                </button>
                            @else 
                                <button type="button" class="btn icon btn-danger"
                                    wire:click="declined({{ $approval->id }}">
                                    <i class="bi bi-x"></i>
                                    Decline
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        @endif

        @foreach ($functs as $funct)
            @php
                $number = 1;
            @endphp
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
                            @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                {{ $sub_percentage->value }}%
                            @endif
                        </h5>
                        @foreach ($user->outputs()->where('sub_funct_id', $sub_funct->id)->get() as $output)
                            @if ($output->type == 'opcr' &&
                                $output->duration_id == $duration->id &&
                                $output->user_type == $user_type)
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{ $output->code }} {{ $number++ }}
                                            {{ $output->output }}
                                        </h4>
                                        <p class="text-subtitle text-muted"></p>
                                    </div>
                                    @forelse ($user->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                                        <div class="card-body">
                                            <h6>
                                                {{ $suboutput->suboutput }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="accordion accordion-flush"
                                                id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                                <div class="row">
                                                    @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                                        <div class="col-12 col-sm-4">
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
                                                                        <td rowspan="2">Target Output</td>
                                                                        <td rowspan="2">Alloted Budget</td>
                                                                        <td rowspan="2">Responsible Person/Office</td>
                                                                        <td rowspan="2">Actual
                                                                            Accomplishment</td>
                                                                        <td colspan="4">Rating</td>
                                                                        <td rowspan="2">Remarks</td>
                                                                        <td rowspan="2">Action</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Q</td>
                                                                        <td>E</td>
                                                                        <td>T</td>
                                                                        <td>A</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        @if (isset($target->pivot->target_output))
                                                                            <td style="white-space: nowrap;">
                                                                                {{ $target->pivot->target_output }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $target->pivot->alloted_budget }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $target->pivot->responsible }}
                                                                            </td>
                                                                        @else
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        @endif
                            
                                                                        @if ($approval->name == 'assess')
                                                                            @foreach ($target->ratings as $rating)
                                                                                @if ($rating->user_id == $user->id) 
                                                                                    <td>
                                                                                        {{ $rating->accomplishment }}
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($rating->quality)
                                                                                        {{ $rating->quality }}
                                                                                        @else
                                                                                        NR
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($rating->efficiency)
                                                                                            {{ $rating->efficiency }}
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
                                                                                    <td class="text-nowrap">{{ $rating->average }}
                                                                                    </td>
                                                                                    <td>{{ $rating->remarks }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="hstack align-items-center justify-content-center gap-2">
                                                                                            @if ($approval->approve_id != auth()->user()->id)
                                                                                                <button type="button"
                                                                                                    class="btn icon btn-success"
                                                                                                    data-bs-toggle="modal"
                                                                                                    data-bs-target="#EditRatingModal"
                                                                                                    wire:click="editRating({{ $rating->id }})"
                                                                                                    title="Edit Rating">
                                                                                                    <i class="bi bi-pencil-square"></i>
                                                                                                </button>
                                                                                            @else
                                                                                                <button type="button"
                                                                                                    class="btn icon btn-primary"
                                                                                                    data-bs-toggle="modal"
                                                                                                    data-bs-target="#CommentModal"
                                                                                                    wire:click="comment({{ $target->id }})"
                                                                                                    title="Add Comment">
                                                                                                    <i class="bi bi-chat-dots"></i>
                                                                                                </button>
                                                                                            @endif
                                                                                            <div class="dropup-center dropup">
                                                                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                    <i class="bi bi-file-earmark-check"></i>
                                                                                                </button>
                                                                                                <ul class="dropdown-menu p-2 border-1" style="width: 600px">
                                                                                                    <li>
                                                                                                        <div class="row border-bottom">
                                                                                                            <div class="col-3 border-end">
                                                                                                                <div class="row">
                                                                                                                    <div class="col-12 text-center text-nowrap">
                                                                                                                        <small><strong>Old Score</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>Q</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>E</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>T</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>A</strong></small>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-3 border-end">
                                                                                                                <div class="row">
                                                                                                                    <div class="col-12 text-center text-nowrap">
                                                                                                                        <small><strong>New Score</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>Q</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>E</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>T</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>A</strong></small>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-3 text-center text-nowrap border-end">
                                                                                                                <small><strong>Updated By</strong></small>
                                                                                                            </div>
                                                                                                            <div class="col-3 text-center text-nowrap">
                                                                                                                <small><strong>Updated At</strong></small>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                        @foreach ($rating->score_logs()->where('user_id', auth()->user()->id)->get() as $score_log)
                                                                                                            <li>
                                                                                                                <div class="row">
                                                                                                                    <div class="col-3 border-end">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_qua }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_eff }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_time }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_ave }}</small>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 border-end">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_qua }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_eff }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_time }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_ave }}</small>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap border-end">
                                                                                                                        <small>{{ $score_log->updatedBy->name }}</small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small>{{ $score_log->updated_at->diffForHumans() }}</small>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </li>
                                                                                                        @endforeach
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    @break
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            <td colspan="6"></td>
                                                                            <td>
                                                                                <div class="hstack gap-2">
                                                                                    <button type="button"
                                                                                        class="btn icon btn-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#CommentModal"
                                                                                        wire:click="comment({{ $target->id }})"
                                                                                        title="Add Comment">
                                                                                        <i class="bi bi-chat-dots"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        @endif
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
                                                <div class="row">
                                                    @foreach ($user->targets()->where('output_id', $output->id)->get() as $target)
                                                        <div class="col-12 col-sm-4">
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
                                                                        <td rowspan="2">Target Output</td>
                                                                        <td rowspan="2">Alloted Budget</td>
                                                                        <td rowspan="2">Responsible Person/Office</td>
                                                                        <td rowspan="2">Actual
                                                                            Accomplishment</td>
                                                                        <td colspan="4">Rating</td>
                                                                        <td rowspan="2">Remarks</td>
                                                                        <td rowspan="2">Action</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Q</td>
                                                                        <td>E</td>
                                                                        <td>T</td>
                                                                        <td>A</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        @if (isset($target->pivot->target_output))
                                                                            <td style="white-space: nowrap;">
                                                                                {{ $target->pivot->target_output }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $target->pivot->alloted_budget }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $target->pivot->responsible }}
                                                                            </td>
                                                                        @else
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        @endif
                            
                                                                        @if ($approval->name == 'assess')
                                                                            @foreach ($target->ratings as $rating)
                                                                                @if ($rating->user_id == $user->id) 
                                                                                    <td>{{ $rating->accomplishment }}
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($rating->quality)
                                                                                        {{ $rating->quality }}
                                                                                        @else
                                                                                        NR
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($rating->efficiency)
                                                                                            {{ $rating->efficiency }}
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
                                                                                    <td class="text-nowrap">{{ $rating->average }}
                                                                                    </td>
                                                                                    <td>{{ $rating->remarks }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="hstack align-items-center justify-content-center gap-2">
                                                                                            @if ($approval->approve_id != auth()->user()->id)
                                                                                                <button type="button"
                                                                                                    class="btn icon btn-success"
                                                                                                    data-bs-toggle="modal"
                                                                                                    data-bs-target="#EditRatingModal"
                                                                                                    wire:click="editRating({{ $rating->id }})"
                                                                                                    title="Edit Rating">
                                                                                                    <i class="bi bi-pencil-square"></i>
                                                                                                </button>
                                                                                            @else
                                                                                                <button type="button"
                                                                                                    class="btn icon btn-primary"
                                                                                                    data-bs-toggle="modal"
                                                                                                    data-bs-target="#CommentModal"
                                                                                                    wire:click="comment({{ $target->id }})"
                                                                                                    title="Add Comment">
                                                                                                    <i class="bi bi-chat-dots"></i>
                                                                                                </button>
                                                                                            @endif
                                                                                            <div class="dropup-center dropup">
                                                                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                    <i class="bi bi-file-earmark-check"></i>
                                                                                                </button>
                                                                                                <ul class="dropdown-menu p-2 border-1" style="width: 600px">
                                                                                                    <li>
                                                                                                        <div class="row border-bottom">
                                                                                                            <div class="col-3 border-end">
                                                                                                                <div class="row">
                                                                                                                    <div class="col-12 text-center text-nowrap">
                                                                                                                        <small><strong>Old Score</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>Q</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>E</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>T</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>A</strong></small>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-3 border-end">
                                                                                                                <div class="row">
                                                                                                                    <div class="col-12 text-center text-nowrap">
                                                                                                                        <small><strong>New Score</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>Q</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>E</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>T</strong></small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small><strong>A</strong></small>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-3 text-center text-nowrap border-end">
                                                                                                                <small><strong>Updated By</strong></small>
                                                                                                            </div>
                                                                                                            <div class="col-3 text-center text-nowrap">
                                                                                                                <small><strong>Updated At</strong></small>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                        @foreach ($rating->score_logs()->where('user_id', auth()->user()->id)->get() as $score_log)
                                                                                                            <li>
                                                                                                                <div class="row">
                                                                                                                    <div class="col-3 border-end">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_qua }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_eff }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_time }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-danger' }}">
                                                                                                                                <small>{{ $score_log->old_ave }}</small>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 border-end">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_qua }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_eff }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_time }}</small>
                                                                                                                            </div>
                                                                                                                            <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-success' }}">
                                                                                                                                <small>{{ $score_log->new_ave }}</small>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap border-end">
                                                                                                                        <small>{{ $score_log->updatedBy->name }}</small>
                                                                                                                    </div>
                                                                                                                    <div class="col-3 text-center text-nowrap">
                                                                                                                        <small>{{ $score_log->updated_at->diffForHumans() }}</small>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </li>
                                                                                                        @endforeach
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    @break
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            <td colspan="6"></td>
                                                                            <td>
                                                                                <div class="hstack gap-2">
                                                                                    <button type="button"
                                                                                        class="btn icon btn-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#CommentModal"
                                                                                        wire:click="comment({{ $target->id }})"
                                                                                        title="Add Comment">
                                                                                        <i class="bi bi-chat-dots"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        @endif
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
                    $output->user_type == $user_type)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{ $output->code }} {{ $number++ }} {{ $output->output }}
                            </h4>
                            <p class="text-subtitle text-muted"></p>
                        </div>
                        @forelse ($user->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                            <div class="card-body">
                                <h6>
                                    {{ $suboutput->suboutput }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="accordion accordion-flush"
                                    id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                    <div class="row">
                                        @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                            <div class="col-12 col-sm-4">
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
                                                            <td rowspan="2">Target Output</td>
                                                            <td rowspan="2">Alloted Budget</td>
                                                            <td rowspan="2">Responsible Person/Office</td>
                                                            <td rowspan="2">Actual
                                                                Accomplishment</td>
                                                            <td colspan="4">Rating</td>
                                                            <td rowspan="2">Remarks</td>
                                                            <td rowspan="2">Action</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Q</td>
                                                            <td>E</td>
                                                            <td>T</td>
                                                            <td>A</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            @if (isset($target->pivot->target_output))
                                                                <td style="white-space: nowrap;">
                                                                    {{ $target->pivot->target_output }}
                                                                </td>
                                                                <td>
                                                                    {{ $target->pivot->alloted_budget }}
                                                                </td>
                                                                <td>
                                                                    {{ $target->pivot->responsible }}
                                                                </td>
                                                            @else
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            @endif
                
                                                            @if ($approval->name == 'assess')
                                                                @foreach ($target->ratings as $rating)
                                                                    @if ($rating->user_id == $user->id) 
                                                                        <td>{{ $rating->accomplishment }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->quality)
                                                                            {{ $rating->quality }}
                                                                            @else
                                                                            NR
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->efficiency)
                                                                                {{ $rating->efficiency }}
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
                                                                        <td class="text-nowrap">{{ $rating->average }}
                                                                        </td>
                                                                        <td>{{ $rating->remarks }}
                                                                        </td>
                                                                        <td>
                                                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                                                @if ($approval->approve_id != auth()->user()->id)
                                                                                    <button type="button"
                                                                                        class="btn icon btn-success"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#EditRatingModal"
                                                                                        wire:click="editRating({{ $rating->id }})"
                                                                                        title="Edit Rating">
                                                                                        <i class="bi bi-pencil-square"></i>
                                                                                    </button>
                                                                                @else
                                                                                    <button type="button"
                                                                                        class="btn icon btn-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#CommentModal"
                                                                                        wire:click="comment({{ $target->id }})"
                                                                                        title="Add Comment">
                                                                                        <i class="bi bi-chat-dots"></i>
                                                                                    </button>
                                                                                @endif
                                                                                <div class="dropup-center dropup">
                                                                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                        <i class="bi bi-file-earmark-check"></i>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu p-2 border-1" style="width: 600px">
                                                                                        <li>
                                                                                            <div class="row border-bottom">
                                                                                                <div class="col-3 border-end">
                                                                                                    <div class="row">
                                                                                                        <div class="col-12 text-center text-nowrap">
                                                                                                            <small><strong>Old Score</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>Q</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>E</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>T</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>A</strong></small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-3 border-end">
                                                                                                    <div class="row">
                                                                                                        <div class="col-12 text-center text-nowrap">
                                                                                                            <small><strong>New Score</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>Q</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>E</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>T</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>A</strong></small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-3 text-center text-nowrap border-end">
                                                                                                    <small><strong>Updated By</strong></small>
                                                                                                </div>
                                                                                                <div class="col-3 text-center text-nowrap">
                                                                                                    <small><strong>Updated At</strong></small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                            @foreach ($rating->score_logs()->where('user_id', auth()->user()->id)->get() as $score_log)
                                                                                                <li>
                                                                                                    <div class="row">
                                                                                                        <div class="col-3 border-end">
                                                                                                            <div class="row">
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_qua }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_eff }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_time }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_ave }}</small>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-3 border-end">
                                                                                                            <div class="row">
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_qua }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_eff }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_time }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_ave }}</small>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap border-end">
                                                                                                            <small>{{ $score_log->updatedBy->name }}</small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small>{{ $score_log->updated_at->diffForHumans() }}</small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </li>
                                                                                            @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <td colspan="6"></td>
                                                                <td>
                                                                    <div class="hstack gap-2">
                                                                        <button type="button"
                                                                            class="btn icon btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#CommentModal"
                                                                            wire:click="comment({{ $target->id }})"
                                                                            title="Add Comment">
                                                                            <i class="bi bi-chat-dots"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            @endif
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
                                    <div class="row">
                                        @foreach ($user->targets()->where('output_id', $output->id)->get() as $target)
                                            <div class="col-12 col-sm-4">
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
                                            </div>
                                        @endforeach
                                    </div>

                                    @foreach ($user->targets()->where('output_id', $output->id)->get() as $target)
                                        <div wire:ignore.self
                                            id="{{ 'target' }}{{ $target->id }}"
                                            class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                            data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                            <div class="accordion-body table-responsive">
                                                <table class="table table-lg text-center">
                                                    <thead>
                                                        <tr>
                                                            <td rowspan="2">Target Output</td>
                                                            <td rowspan="2">Alloted Budget</td>
                                                            <td rowspan="2">Responsible Person/Office</td>
                                                            <td rowspan="2">Actual
                                                                Accomplishment</td>
                                                            <td colspan="4">Rating</td>
                                                            <td rowspan="2">Remarks</td>
                                                            <td rowspan="2">Action</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Q</td>
                                                            <td>E</td>
                                                            <td>T</td>
                                                            <td>A</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            @if (isset($target->pivot->target_output))
                                                                <td style="white-space: nowrap;">
                                                                    {{ $target->pivot->target_output }}
                                                                </td>
                                                                <td>
                                                                    {{ $target->pivot->alloted_budget }}
                                                                </td>
                                                                <td>
                                                                    {{ $target->pivot->responsible }}
                                                                </td>
                                                            @else
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            @endif
                
                                                            @if($approval->name == 'assess')
                                                                @foreach ($target->ratings as $rating)
                                                                    @if ($rating->user_id == $user->id) 
                                                                        <td>{{ $rating->accomplishment }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->quality)
                                                                            {{ $rating->quality }}
                                                                            @else
                                                                            NR
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($rating->efficiency)
                                                                                {{ $rating->efficiency }}
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
                                                                        <td class="text-nowrap">{{ $rating->average }}
                                                                        </td>
                                                                        <td>{{ $rating->remarks }}
                                                                        </td>
                                                                        <td>
                                                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                                                @if ($approval->approve_id != auth()->user()->id)
                                                                                    <button type="button"
                                                                                        class="btn icon btn-success"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#EditRatingModal"
                                                                                        wire:click="editRating({{ $rating->id }})"
                                                                                        title="Edit Rating">
                                                                                        <i class="bi bi-pencil-square"></i>
                                                                                    </button>
                                                                                @else
                                                                                    <button type="button"
                                                                                        class="btn icon btn-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#CommentModal"
                                                                                        wire:click="comment({{ $target->id }})"
                                                                                        title="Add Comment">
                                                                                        <i class="bi bi-chat-dots"></i>
                                                                                    </button>
                                                                                @endif
                                                                                <div class="dropup-center dropup">
                                                                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                        <i class="bi bi-file-earmark-check"></i>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu p-2 border-1" style="width: 600px">
                                                                                        <li>
                                                                                            <div class="row border-bottom">
                                                                                                <div class="col-3 border-end">
                                                                                                    <div class="row">
                                                                                                        <div class="col-12 text-center text-nowrap">
                                                                                                            <small><strong>Old Score</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>Q</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>E</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>T</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>A</strong></small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-3 border-end">
                                                                                                    <div class="row">
                                                                                                        <div class="col-12 text-center text-nowrap">
                                                                                                            <small><strong>New Score</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>Q</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>E</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>T</strong></small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small><strong>A</strong></small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-3 text-center text-nowrap border-end">
                                                                                                    <small><strong>Updated By</strong></small>
                                                                                                </div>
                                                                                                <div class="col-3 text-center text-nowrap">
                                                                                                    <small><strong>Updated At</strong></small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                            @foreach ($rating->score_logs()->where('user_id', auth()->user()->id)->get() as $score_log)
                                                                                                <li>
                                                                                                    <div class="row">
                                                                                                        <div class="col-3 border-end">
                                                                                                            <div class="row">
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_qua }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_eff }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_time }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-danger' }}">
                                                                                                                    <small>{{ $score_log->old_ave }}</small>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-3 border-end">
                                                                                                            <div class="row">
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_qua == $score_log->new_qua) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_qua }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_eff == $score_log->new_eff) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_eff }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_time == $score_log->new_time) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_time }}</small>
                                                                                                                </div>
                                                                                                                <div class="col-3 text-center text-nowrap {{ ($score_log->old_ave == $score_log->new_ave) ? '' : 'text-success' }}">
                                                                                                                    <small>{{ $score_log->new_ave }}</small>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap border-end">
                                                                                                            <small>{{ $score_log->updatedBy->name }}</small>
                                                                                                        </div>
                                                                                                        <div class="col-3 text-center text-nowrap">
                                                                                                            <small>{{ $score_log->updated_at->diffForHumans() }}</small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </li>
                                                                                            @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <td colspan="6"></td>
                                                                <td>
                                                                    <div class="hstack gap-2">
                                                                        <button type="button"
                                                                            class="btn icon btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#CommentModal"
                                                                            wire:click="comment({{ $target->id }})"
                                                                            title="Add Comment">
                                                                            <i class="bi bi-chat-dots"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            @endif
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
