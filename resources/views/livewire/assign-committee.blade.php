<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>List of Committees</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Committees</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        <div class="w-100 d-flex justify-content-end">
            <div class="mb-3 hstack gap-3">
                <div class="my-auto form-group position-relative has-icon-right">
                    <input type="text" class="form-control" placeholder="Search.." wire:model="search" maxlength="25">
                    <div class="form-control-icon">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto">Committee - Faculty</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg text-center">
                        <thead>
                            <tr>
                                <th>COMMITTEE NAME</th>
                                <th>NAME</th>
                                <th>COMMITTEE TYPE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($committees as $committee)
                                @if ($committee->type == 'faculty')
                                    <tr>
                                        <td>{{ $committee->name }}</td>
                                        <td>{{ $committee->user->name }}</td>
                                        <td>{{ ($committee->committee_type == 'eval_committee') ? 'Evaluation Committee' : 'Review Committee' }}</td>
                                        <td>
                                            <div class="d-flex gap-3 justify-content-center align-items-center">
                                                <button type="button" class="btn icon btn-success" wire:click="select('faculty', {{ $committee->id }})" data-bs-toggle="modal" data-bs-target="#EditCommitteeModal">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn icon btn-danger" wire:click="select('faculty', {{ $committee->id }})"  data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5">No record available!</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    <button type="button" class="btn icon btn-primary" wire:click="select('faculty')" data-bs-toggle="modal" data-bs-target="#AddCommitteeModal">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto">Committee - Staff</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg text-center">
                        <thead>
                            <tr>
                                <th>COMMITTEE NAME</th>
                                <th>NAME</th>
                                <th>COMMITEE TYPE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($committees as $committee)
                                @if ($committee->type == 'staff')
                                    <tr>
                                        <td>{{ $committee->name }}</td>
                                        <td>{{ $committee->user->name }}</td>
                                        <td>{{ ($committee->committee_type == 'eval_committee') ? 'Evaluation Committee' : 'Review Committee' }}</td>
                                        <td>
                                            <div class="d-flex gap-3 justify-content-center align-items-center">
                                                <button type="button" class="btn icon btn-success" wire:click="select('staff', {{ $committee->id }})" data-bs-toggle="modal" data-bs-target="#EditCommitteeModal">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn icon btn-danger" wire:click="select('staff', {{ $committee->id }})"  data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5">No record available!</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    <button type="button" class="btn icon btn-primary" wire:click="select('staff')" data-bs-toggle="modal" data-bs-target="#AddCommitteeModal">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <x-modals :users="$users" />
</div>
