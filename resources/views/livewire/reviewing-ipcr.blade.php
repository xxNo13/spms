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
                                    <th>REVIEWING AS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviewing_scores as $ipcr)
                                    @if ($ipcr->prog_chair_id == auth()->user()->id)
                                        <tr>
                                            <td>{{ $ipcr->user->name }}</td>
                                            <td>{{ $ipcr->user->email }}</td>
                                            <td>
                                                @foreach ($ipcr->user->offices as $office)
                                                    @if ($loop->last)
                                                        {{ $office->office_abbr }}
                                                        @break
                                                    @endif
                                                    {{ $office->office_abbr }} <br/>
                                                @endforeach    
                                            </td>
                                            <td>Program Chairperson</td>
                                            <td>
                                                <div class="hstack justify-content-center align-items-center gap-3">
                                                    @if ($ipcr->prog_chair_status)
                                                        Approved
                                                    @endif
                                                    <button class="btn icon btn-secondary" wire:click="viewed({{ $ipcr->id }}, 'reviewing', 'prog_chair_status')">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @if (($ipcr->designated_id && $ipcr->prog_chair_status && $ipcr->designated_id == auth()->user()->id))
                                        <tr>
                                            <td>{{ $ipcr->user->name }}</td>
                                            <td>{{ $ipcr->user->email }}</td>
                                            <td>
                                                @foreach ($ipcr->user->offices as $office)
                                                    @if ($loop->last)
                                                        {{ $office->office_abbr }}
                                                        @break
                                                    @endif
                                                    {{ $office->office_abbr }} <br/>
                                                @endforeach    
                                            </td>
                                            <td>Designated Head</td>
                                            <td>
                                                <div class="hstack justify-content-center align-items-center gap-3">
                                                    @if ($ipcr->designated_status)
                                                        Approved
                                                    @endif
                                                    <button class="btn icon btn-secondary" wire:click="viewed({{ $ipcr->id }}, 'reviewing', 'designated_status')">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach (auth()->user()->offices as $office)
                                        @if ((str_contains(strtolower($office->office_abbr), 'hr') || str_contains(strtolower($office->office_name), 'hr')) && (($ipcr->designated_id && $ipcr->designated_status) || (!$ipcr->designated_id && $ipcr->prog_chair_status)))
                                            <tr>
                                                <td>{{ $ipcr->user->name }}</td>
                                                <td>{{ $ipcr->user->email }}</td>
                                                <td>
                                                    @foreach ($ipcr->user->offices as $office)
                                                        @if ($loop->last)
                                                            {{ $office->office_abbr }}
                                                            @break
                                                        @endif
                                                        {{ $office->office_abbr }} <br/>
                                                    @endforeach    
                                                </td>
                                                <td>Human Resource</td>
                                                <td>
                                                    <div class="hstack justify-content-center align-items-center gap-3">
                                                        @if ($ipcr->hr_status)
                                                            Approved
                                                        @endif
                                                        <button class="btn icon btn-secondary" wire:click="viewed({{ $ipcr->id }}, 'reviewing', 'hr_status')">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @break
                                        @endif
                                    @endforeach
                                    @if (in_array(auth()->user()->id, $eval_committees) && $ipcr->hr_status)
                                        <tr>
                                            <td>{{ $ipcr->user->name }}</td>
                                            <td>{{ $ipcr->user->email }}</td>
                                            <td>
                                                @foreach ($ipcr->user->offices as $office)
                                                    @if ($loop->last)
                                                        {{ $office->office_abbr }}
                                                        @break
                                                    @endif
                                                    {{ $office->office_abbr }} <br/>
                                                @endforeach    
                                            </td>
                                            <td>Evaluation Committee</td>
                                            <td>
                                                <div class="hstack justify-content-center align-items-center gap-3">
                                                    @if ($ipcr->eval_committee_status)
                                                        Approved
                                                    @endif
                                                    <button class="btn icon btn-secondary" wire:click="viewed({{ $ipcr->id }}, 'reviewing', 'eval_committee_status')">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @if (in_array(auth()->user()->id, $review_committees) && $ipcr->eval_committee_status)
                                        <tr>
                                            <td>{{ $ipcr->user->name }}</td>
                                            <td>{{ $ipcr->user->email }}</td>
                                            <td>
                                                @foreach ($ipcr->user->offices as $office)
                                                    @if ($loop->last)
                                                        {{ $office->office_abbr }}
                                                        @break
                                                    @endif
                                                    {{ $office->office_abbr }} <br/>
                                                @endforeach    
                                            </td>
                                            <td>Review Committtee</td>
                                            <td>
                                                <div class="hstack justify-content-center align-items-center gap-3">
                                                    @if ($ipcr->review_committee_status)
                                                        Approved
                                                    @endif
                                                    <button class="btn icon btn-secondary" wire:click="viewed({{ $ipcr->id }}, 'reviewing', 'review_committee_status')">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
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
    </section>

    <x-modals />
</div>
