<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>List of Subordinates</h3>
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
                <div class="ms-auto my-auto form-group position-relative has-icon-right">
                    <input type="text" class="form-control" placeholder="Search.." wire:model="search">
                    <div class="form-control-icon">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="review-tab" data-bs-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="true">To Review</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="approval-tab" data-bs-toggle="tab" href="#approval" role="tab" aria-controls="approval" aria-selected="false" tabindex="-1">To Approve</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show" id="review" role="tabpanel" aria-labelledby="review-tab">
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
                                        @if ((in_array($approval->id, auth()->user()->user_approvals()->pluck('approval_id')->toArray()) || (in_array(auth()->user()->id, $pmts) && $approval->type == 'opcr' && $approval->user_type == 'office')) &&
                                            ($duration && $approval->duration_id == $duration->id))
                                            <tr>
                                                <td>{{ $approval->user->name }}</td>
                                                <td>{{ $approval->user->email }}</td>
                                                <td>
                                                    <div class="d-md-flex flex-column gap-3 justify-content-center">
                                                        @foreach ($approval->user->offices()->wherePivot('isHead', true)->get() as $office)
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
                                                    Reviewed
                                                </td>
                                                <td>
                                                    @if ((auth()->user()->user_approvals()->where('approval_id', $approval->id)->first() && auth()->user()->user_approvals()->where('approval_id', $approval->id)->first()->pivot->review_status == 1) || (in_array(auth()->user()->id, $pmts) && $approval->reviewers()->wherePivot('review_status', 2)->first()))
                                                        Approved
                                                    @elseif (auth()->user()->user_approvals()->where('approval_id', $approval->id)->first() && auth()->user()->user_approvals()->where('approval_id', $approval->id)->first()->pivot->review_status == 2)
                                                        Declined
                                                    @elseif ((auth()->user()->user_approvals()->where('approval_id', $approval->id)->first() && auth()->user()->user_approvals()->where('approval_id', $approval->id)->first()->pivot->review_status == 3) || (in_array(auth()->user()->id, $pmts) && $approval->reviewers()->wherePivot('review_status', 2)->first()))
                                                        Declined by the other Reviewer
                                                    @else
                                                        @if ($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d'))
                                                            <div class="hstack gap-2 justify-content-center">
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
                                                                    wire:click="viewed({{ $approval->user_id }}, '{{ $approval->type }}', '{{ 'for-approval' }}', '{{ $approval->user_type }}')">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endif
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
                    <div class="tab-pane fade" id="approval" role="tabpanel" aria-labelledby="approval-tab">
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
                                            ($duration && $approval->duration_id == $duration->id))
                                            <tr>
                                                <td>{{ $approval->user->name }}</td>
                                                <td>{{ $approval->user->email }}</td>
                                                <td>
                                                    <div class="d-md-flex flex-column gap-3 justify-content-center">
                                                        @foreach ($approval->user->offices()->wherePivot('isHead', true)->get() as $office)
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
                                                    Approval
                                                </td>
                                                <td>
                                                    @if ($approval->approve_status == 1)
                                                        Approved
                                                    @elseif ($approval->approve_status == 2)
                                                        Declined
                                                    @elseif ($approval->approve_status == 3)
                                                        Declined by Reviewer
                                                    @else
                                                        @if ($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d'))
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
                                                        
                                                            <div class="hstack gap-2 justify-content-center">
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
                                                                    wire:click="viewed({{ $approval->user_id }}, '{{ $approval->type }}', '{{ 'for-approval' }}', '{{ $approval->user_type }}')">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endif
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
