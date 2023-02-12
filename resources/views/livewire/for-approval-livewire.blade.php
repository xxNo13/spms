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
                <div class="ms-auto my-auto form-group position-relative has-icon-right">
                    <input type="text" class="form-control" placeholder="Search.." wire:model="search">
                    <div class="form-control-icon">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
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
                            @forelse ($approvals as $approval)
                                @if ((Auth::user()->id == $approval->review_id || Auth::user()->id == $approval->approve_id) &&
                                    ($duration && $approval->duration_id == $duration->id))
                                    <tr>
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
                                            @if (Auth::user()->id == $approval->review_id)
                                                Reviewed
                                            @elseif (Auth::user()->id == $approval->approve_id)
                                                Approval
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->id == $approval->review_id)
                                                @if ($approval->review_status == 1)
                                                    Approved
                                                @elseif ($approval->review_status == 2)
                                                    Declined
                                                @else
                                                    @if ($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d'))
                                                        <div class="hstack gap-2 justify-content-center">
                                                            <button type="button" class="btn icon btn-info"
                                                                wire:click="approved({{ $approval->id }})">
                                                                <i class="bi bi-check"></i>
                                                            </button>
                                                            <button type="button" class="btn icon btn-danger"
                                                                wire:click="clickdeclined({{ $approval->id }})"  data-bs-toggle="modal" data-bs-target="#DeclineModal">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                            <button type="button" class="btn icon btn-secondary"
                                                                wire:click="viewed({{ $approval->user_id }}, '{{ $approval->type }}', '{{ 'for-approval' }}', '{{ $approval->user_type }}')">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endif
                                            @elseif (Auth::user()->id == $approval->approve_id)
                                                @if ($approval->approve_status == 1)
                                                    Approved
                                                @elseif ($approval->approve_status == 2)
                                                    Declined
                                                @else
                                                    @if ($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d'))
                                                        <div class="hstack gap-2 justify-content-center">
                                                            @if ($approval->review_status == 1)
                                                                <button type="button" class="btn icon btn-info"
                                                                    wire:click="approved({{ $approval->id }})">
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
    </section>

    
    {{ $approvals->links('components.pagination') }}
    <x-modals />
</div>
