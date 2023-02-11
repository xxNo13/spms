<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tracking Tool for Monitoring Assignments</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a
                                href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">TTMA</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="toastify on  toastify-right toastify-bottom" aria-live="polite"
            style="background: rgb(79, 190, 135); transform: translate(0px, 0px); bottom: 15px;">
            {{ session('message') }}
        </div>
    @endif

    <section class="section pt-3">
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto">Your Assignments</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg text-center">
                        <thead>
                            <tr>
                                <th>TASK ID No.</th>
                                <th>SUBJECT</th>
                                <th>OUTPUT</th>
                                <th>DATE ASSIGNED</th>
                                <th>DATE ACCOMPLISHED</th>
                                <th>DATE DEADLINE</th>
                                <th style="white-space: nowrap;">OFFICER MESSAGE</th>
                                <th>REMARKS</th>
                                <th style="white-space: nowrap;">HEAD COMMENTS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignments as $ttma)
                                @if ($duration && $ttma->duration_id == $duration->id)
                                    <tr>
                                        <td>{{ sprintf('%03u', $ttma->id) }}</td>
                                        <td>{{ $ttma->subject }}</td>
                                        <td>{{ $ttma->output }}</td>
                                        <td>{{ date('M d, Y', strtotime($ttma->created_at)) }}</td>
                                        <td>
                                            @if ($ttma->remarks)
                                                {{ date('M d, Y', strtotime($ttma->updated_at)) }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="
                                            @if ($ttma->deadline < date('Y-m-d') && empty($ttma->remarks))
                                                text-danger
                                            @endif">
                                                {{ date('M d, Y', strtotime($ttma->deadline)) }}
                                            </span>
                                        </td>
                                        <td>{{ $ttma->message }}</td>
                                        <td>{{ $ttma->remarks }}</td>
                                        <td>{{ $ttma->comments }}</td>
                                        <td>
                                            @if (!$ttma->message)
                                                <button type="button" class="btn icon btn-info"
                                                    data-bs-toggle="modal" data-bs-target="#MessageTTMAModal"
                                                    wire:click="select('message',{{ $ttma->id }})">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="10">No record available!</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <div class="hstack">
                                <div class="ms-auto">
                                    {{ $assignments->links('components.pagination') }}
                                </div>
                            </div>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if (Auth::user()->offices()->wherePivot('isHead', true)->first())
            <hr>

            <div class="card">
                <div class="card-header hstack">
                    <h4 class="card-title my-auto">Subordinate's Assignments</h4>
                    <div class="hstack ms-auto gap-3">
                        <div class="ms-auto my-auto form-group position-relative has-icon-right">
                            <input type="text" class="form-control" placeholder="Search.." wire:model="search">
                            <div class="form-control-icon">
                                <i class="bi bi-search"></i>
                            </div>
                        </div>
                        @if ($duration)
                            <a href="/print/ttma" target="_blank" class="ms-auto btn icon btn-primary" title="Print TTMA">
                                <i class="bi bi-printer"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-lg text-center">
                            <thead>
                                <tr>
                                    <th>TASK ID No.</th>
                                    <th>SUBJECT</th>
                                    <th>ACTION OFFICER</th>
                                    <th>OUTPUT</th>
                                    <th>DATE ASSIGNED</th>
                                    <th>DATE ACCOMPLISHED</th>
                                    <th>DATE DEADLINE</th>
                                    <th style="white-space: nowrap;">OFFICER MESSAGE</th>
                                    <th>REMARKS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ttmas as $ttma)
                                    @if ($duration && $ttma->duration_id == $duration->id)
                                        <tr>
                                            <td>{{ sprintf('%03u', $ttma->id) }}</td>
                                            <td>{{ $ttma->subject }}</td>
                                            <td>{{ $ttma->user->name }}</td>
                                            <td>{{ $ttma->output }}</td>
                                            <td>{{ date('M d, Y', strtotime($ttma->created_at)) }}</td>
                                            <td>
                                                @if ($ttma->remarks)
                                                    {{ date('M d, Y', strtotime($ttma->updated_at)) }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="
                                                @if ($ttma->deadline < date('Y-m-d') && empty($ttma->remarks))
                                                    text-danger
                                                @endif">
                                                    {{ date('M d, Y', strtotime($ttma->deadline)) }}
                                                </span>
                                            </td>
                                            <td>{{ $ttma->message }}</td>
                                            <td>{{ $ttma->remarks }}</td>
                                            <td>
                                                @if ($ttma->message && !$ttma->comments && (!$ttma->remarks && ($ttma->duration_id == $duration->id)) && ($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d')))
                                                    <div class="hstack gap-2">
                                                        <button type="button" class="btn icon btn-info"
                                                            data-bs-toggle="modal" data-bs-target="#DoneModal"
                                                            wire:click="select('assign', {{ $ttma->id }})">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                        <button type="button" class="btn icon btn-danger"
                                                            data-bs-toggle="modal" data-bs-target="#DeclineModal"
                                                            wire:click="select('decline', {{ $ttma->id }})">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </div>
                                                @elseif (!$ttma->comments && (!$ttma->remarks && ($ttma->duration_id == $duration->id)) && ($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d')))
                                                   <div class="hstack gap-2">
                                                        <button type="button" class="btn icon btn-success"
                                                            data-bs-toggle="modal" data-bs-target="#EditTTMAModal"
                                                            wire:click="select('assign', {{ $ttma->id }}, '{{ 'edit' }}')">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button type="button" class="btn icon btn-danger"
                                                            data-bs-toggle="modal" data-bs-target="#DeleteModal"
                                                            wire:click="select('assign',{{ $ttma->id }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                   </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @elseif($loop->last)
                                        <tr>
                                            <td colspan="10">No record available!</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="10">No record available!</td>
                                    </tr>
                                @endforelse
                                @if ($duration && $duration->start_date <= date('Y-m-d') && $duration->end_date >= date('Y-m-d'))
                                    <tr>
                                        <td colspan="9"></td>
                                        <td>
                                            <button type="button" class="btn icon btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#AddTTMAModal" wire:click="select('assign')">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <div class="hstack">
                                    <div class="ms-auto">
                                        {{ $ttmas->links('components.pagination') }}
                                    </div>
                                </div>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <x-modals :users="$users" />
</div>
