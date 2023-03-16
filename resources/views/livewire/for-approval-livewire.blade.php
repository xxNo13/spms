<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>For Approvals</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">For Approval</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto"></h4>
                <div class="hstack gap-3">
                    <div class="form-group">
                        <label for="filterA">Remarks</label>
                        <select class="form-select" name="filterA" id="filterA" wire:model="filterA">
                            <option value="" selected>None</option>
                            <option value="remark">With Remark</option>
                            <option value="noremark">No Remark</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul wire:ignore class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="review-tab" data-bs-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="true">To Review</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="approval-tab" data-bs-toggle="tab" href="#approval" role="tab" aria-controls="approval" aria-selected="false" tabindex="-1">To Approve</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div wire:ignore.self class="tab-pane fade show" id="review" role="tabpanel" aria-labelledby="review-tab">
                        <div class="table-responsive">
                            <table class="table table-lg text-center">
                                <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>OFFICE</th>
                                        <th>TYPE</th>
                                        <th>PURPOSE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($approvals->sortByDesc('updated_at') as $approval)
                                        @if ((in_array($approval->id, auth()->user()->user_approvals()->pluck('approval_id')->toArray())) &&
                                            (($approval->user_type == 'staff' && $approval->duration_id == $durationS->id) || ($approval->user_type == 'faculty' && $approval->duration_id == $durationF->id) || ($approval->user_type == 'office' && $approval->duration_id == $durationO->id)))
                                            <tr wire:key="approval{{ $approval->id }}">
                                                <td>{{ $approval->user->name }}</td>
                                                <td>{{ $approval->user->email }}</td>
                                                <td>
                                                    <div class="d-md-flex flex-column gap-3 justify-content-center">
                                                        @foreach ($approval->user->offices as $office)
                                                            @if ($loop->last)
                                                                {{ $office->office_abbr }}
                                                                @break
                                                            @endif
                                                            {{ $office->office_abbr }} <br/> 
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>{{ strtoupper($approval->type) }}
                                                    @if ($approval->type != 'opcr')
                                                        - {{ strtoupper($approval->user_type) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($approval->name == 'assess')
                                                        Assessing of Score
                                                    @elseif ($approval->name == 'approval')
                                                        Approval of IPCR
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-center">      
                                                        @if ((($curr_app = auth()->user()->user_approvals()->where('approval_id', $approval->id)->first()) && auth()->user()->user_approvals()->where('approval_id', $approval->id)->first()->pivot->review_status == 1))
                                                            Approved
                                                            @if (auth()->user()->user_approvals()->orderBy('updated_at', 'DESC')->where('name', $curr_app->name)->where('type', $curr_app->type)->where('user_type', $curr_app->user_type)->pluck('id')->first() == $curr_app->id)
                                                                <button type="button" class="btn icon btn-secondary"
                                                                    wire:click="viewed({{ $approval }}, '{{ 'for-approval' }}')">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                            @endif
                                                        @elseif (auth()->user()->user_approvals()->where('approval_id', $approval->id)->first() && auth()->user()->user_approvals()->where('approval_id', $approval->id)->first()->pivot->review_status == 2)
                                                            Declined
                                                        @elseif ((auth()->user()->user_approvals()->where('approval_id', $approval->id)->first() && auth()->user()->user_approvals()->where('approval_id', $approval->id)->first()->pivot->review_status == 3))
                                                            Declined by the other Reviewer
                                                        @else
                                                            @if (auth()->user()->user_approvals()->where('approval_id', $approval->id)->first())
                                                                <button type="button" class="btn icon btn-info"
                                                                    wire:click="approved({{ $approval->id }}, 'Reviewed')">
                                                                    <i class="bi bi-check"></i>
                                                                </button>
                                                                <button type="button" class="btn icon btn-danger"
                                                                    wire:click="clickdeclined({{ $approval->id }})"  data-bs-toggle="modal" data-bs-target="#DeclineModal">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            @endif
                                                            <button type="button" class="btn icon btn-secondary"
                                                                wire:click="viewed({{ $approval }}, '{{ 'for-approval' }}')">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6">No record available!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div wire:ignore.self class="tab-pane fade" id="approval" role="tabpanel" aria-labelledby="approval-tab">
                        <div class="table-responsive">
                            <table class="table table-lg text-center">
                                <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>OFFICE</th>
                                        <th>TYPE</th>
                                        <th>PURPOSE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($approvals->sortByDesc('updated_at') as $approval)
                                        @if ((Auth::user()->id == $approval->approve_id) &&
                                            (($approval->user_type == 'staff' && $approval->duration_id == $durationS->id) || ($approval->user_type == 'faculty' && $approval->duration_id == $durationF->id) || ($approval->user_type == 'office' && $approval->duration_id == $durationO->id)))
                                            <tr wire:key="assess{{ $approval->id }}">
                                                <td>{{ $approval->user->name }}</td>
                                                <td>{{ $approval->user->email }}</td>
                                                <td>
                                                    <div class="d-md-flex flex-column gap-3 justify-content-center">
                                                        @foreach ($approval->user->offices as $office)
                                                            @if ($loop->last)
                                                                {{ $office->office_abbr }}
                                                                @break
                                                            @endif
                                                            {{ $office->office_abbr }} <br/> 
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>{{ strtoupper($approval->type) }}
                                                    @if ($approval->type != 'opcr')
                                                        - {{ strtoupper($approval->user_type) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($approval->name == 'assess')
                                                        Assessing of Score
                                                    @elseif ($approval->name == 'approval')
                                                        Approval of IPCR
                                                    @endif
                                                </td>
                                                <td> 
                                                    <div class="hstack gap-2 justify-content-center">
                                                        @if ($approval->approve_status == 1)
                                                            Approved
                                                            <button type="button" class="btn icon btn-secondary"
                                                                wire:click="viewed({{ $approval }}, '{{ 'for-approval' }}')">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        @elseif ($approval->approve_status == 2)
                                                            Declined
                                                        @elseif ($approval->approve_status == 3)
                                                            Declined by Reviewer
                                                        @else
                                                            @php
                                                                $reviewed = true;
                                                            @endphp
                                                            @foreach($approval->reviewers as $review) 
                                                                @if ($review->pivot->review_status == null)
                                                                    @php
                                                                        $reviewed = false;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                            @if ($reviewed)
                                                                <button type="button" class="btn icon btn-info"
                                                                    wire:click="approved({{ $approval->id }}, 'Approved')">
                                                                    <i class="bi bi-check"></i>
                                                                </button>
                                                                <button type="button" class="btn icon btn-danger"
                                                                    wire:click="clickdeclined({{ $approval->id }})"  data-bs-toggle="modal" data-bs-target="#DeclineModal">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            @endif
                                                            <button type="button" class="btn icon btn-secondary"
                                                                wire:click="viewed({{ $approval }}, '{{ 'for-approval' }}')">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6">No record available!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    {{ $approvals->links('components.pagination') }}
    <x-modals />
</div>
