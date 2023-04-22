<div>
    <div class="page-title">
        <div class="row"  id="durations">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Configuring available data</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a
                                href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Configure</li>
                    </ol>
                </nav>
            </div>
        </div>
        

        <div class="row">
            <div class="hstack gap-3 justify-content-center">
                <a href="{{ route('print.rankings.faculty') }}" target="_blank" class="btn icon btn-primary" title="Print List of Faculty IPCR">
                    <i class="bi bi-printer"></i>
                    List of Faculty IPCR
                </a>
                <a href="{{ route('print.rankings.staff') }}" target="_blank" class="btn icon btn-primary" title="Print List of Staff IPCR">
                    <i class="bi bi-printer"></i>
                    List of Staff IPCR
                </a>
                <a href="{{ route('print.rankings.opcr') }}" target="_blank" class="btn icon btn-primary" title="Print List of OPCR">
                    <i class="bi bi-printer"></i>
                    List of OPCR
                </a>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-12 hstack gap-5 justify-content-center">
                <a href="#durations" class="btn btn-outline-primary">Semester Duration</a>
                <a href="#scoreEqs" class="btn btn-outline-primary">Score Equivalent</a>
                <a href="#standardValue" class="btn btn-outline-primary">Standard Values</a>
                <a href="#offices" class="btn btn-outline-primary">Offices</a>
                <a href="#account_types" class="btn btn-outline-primary">Account Types</a>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        <div class="card">
            <div class="accordion accordion-flush card-header" id="durationAccordion">
                <div class="accordion-item">
                    <div class="accordion-header hstack gap-2" id="flush-headingOne" wire:ignore.self>
                        <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#duration"
                            wire:ignore.self aria-expanded="false" aria-controls="duration" role="button">
                            <h4>Semester Duration</h4>
                        </div>
                    </div>
                    <div id="duration" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne"
                        wire:ignore.self data-bs-parent="#durationAccordion">
                        <div class="acordion-header mt-2 row">
                        </div>
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-lg text-center">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>SEMESTER NAME</th>
                                            <th>START DATE</th>
                                            <th>END DATE</th>
                                            <th>TYPE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($durations as $duration)
                                            <tr>
                                                <td>{{ $duration->id }}</td>
                                                <td>{{ $duration->duration_name }}</td>
                                                <td>{{ date('M d, Y', strtotime($duration->start_date)) }}</td>
                                                <td>{{ date('M d, Y', strtotime($duration->end_date)) }}</td>
                                                <td>{{ ucfirst($duration->type) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">No record available!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer" id="scoreEqs">
                {{ $durations->links('components.pagination') }}
            </div>
        </div>

        <div class="card" >
            <div class="accordion accordion-flush card-header" id="scoreEqAccordion">
                <div class="accordion-item">
                    <div class="accordion-header hstack gap-2" id="flush-headingOne" wire:ignore.self>
                        <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#scoreEq"
                            wire:ignore.self aria-expanded="false" aria-controls="scoreEq" role="button">
                            <h4>Score Equivalent</h4>
                        </div>
                    </div>
                    <div id="scoreEq" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne"
                        wire:ignore.self data-bs-parent="#scoreEqAccordion">
                        <div class="acordion-header mt-2 row">    
                            <div class="hstack justify-content-center gap-2 mt-2 col-12">
                                <button type="button" class="ms-auto btn icon btn-success"
                                    wire:click="select('{{ 'scoreEq' }}', {{ $scoreEq->id }}, '{{ 'edit' }}')"
                                    data-bs-toggle="modal" data-bs-target="#EditScoreEqModal">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                        </div>
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-lg text-center">
                                    <thead>
                                        <tr>
                                            <th>Equivalent</th>
                                            <th>Score From</th>
                                            <th>Score To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Outstanding</td>
                                            <td>{{ $scoreEq->out_from }}</td>
                                            <td>{{ $scoreEq->out_to }}</td>
                                        </tr>
                                        <tr>
                                            <td>Very Satisfactory</td>
                                            <td>{{ $scoreEq->verysat_from }}</td>
                                            <td>{{ $scoreEq->verysat_to }}</td>
                                        </tr>
                                        <tr>
                                            <td>Satisfactory</td>
                                            <td>{{ $scoreEq->sat_from }}</td>
                                            <td>{{ $scoreEq->sat_to }}</td>
                                        </tr>
                                        <tr>
                                            <td>Unsatisfactory</td>
                                            <td>{{ $scoreEq->unsat_from }}</td>
                                            <td>{{ $scoreEq->unsat_to }}</td>
                                        </tr>
                                        <tr>
                                            <td>Poor</td>
                                            <td>{{ $scoreEq->poor_from }}</td>
                                            <td>{{ $scoreEq->poor_to }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer" id="standardValue">
            </div>
        </div>

        <div class="card">
            <div class="accordion accordion-flush card-header" id="standardValueAccordion">
                <div class="accordion-item">
                    <div class="accordion-header hstack gap-2" id="flush-headingOne" wire:ignore.self>
                        <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#standardValue"
                            wire:ignore.self aria-expanded="false" aria-controls="standardValue" role="button">
                            <h4>Standard Values</h4>
                        </div>
                    </div>
                    <div id="standardValue" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne"
                        wire:ignore.self data-bs-parent="#standardValueAccordion">
                        <div class="acordion-header mt-2 row">    
                            <div class="hstack justify-content-center gap-2 mt-2 col-12">
                                <button type="button" class="ms-auto btn icon btn-success"
                                    wire:click="select('{{ 'standardValue' }}', {{ $standardValue->id }}, '{{ 'edit' }}')"
                                    data-bs-toggle="modal" data-bs-target="#EditStandardValueModal">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                        </div>
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-lg text-center">
                                    <thead>
                                        <tr>
                                            <th>Quality</th>
                                            <th>Efficiency</th>
                                            <th>Timeliness</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-top"><?php echo nl2br($standardValue->quality) ?></td>
                                            <td class="align-top"><?php echo nl2br($standardValue->efficiency) ?></td>
                                            <td class="align-top"><?php echo nl2br($standardValue->timeliness) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer" id="offices">
            </div>
        </div>

        <div class="card">
            <div class="accordion accordion-flush card-header" id="officeAccordion">
                <div class="accordion-item">
                    <div class="accordion-header hstack gap-2" id="flush-headingOne" wire:ignore.self>
                        <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#office"
                            wire:ignore.self aria-expanded="false" aria-controls="office" role="button">
                            <h4>Offices</h4>
                        </div>
                    </div>
                    <div id="office" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne"
                        wire:ignore.self data-bs-parent="#officeAccordion">
                        <div class="acordion-header mt-2 row">
                            <div class="hstack justify-content-center justify-content-md-start col-12 col-md-6 gap-5 order-md-1 order-last">
                                <div class="my-auto form-group position-relative">
                                    <label for="ascOffice">Order By:</label>
                                    <select class="form-control" wire:model="ascOffice" id="ascOffice">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>
                                    </select>
                                </div>
                                <div class="my-auto form-group position-relative">
                                    <label for="sortOffice">Sort By:</label>
                                    <select class="form-control" wire:model="sortOffice" id="sortOffice">
                                        <option value="id">ID</option>
                                        <option value="office">Office Name</option>
                                        <option value="abr">Office Abbreviation</option>
                                        <option value="parent_id">Head Office</option>
                                    </select>
                                </div>
                                <div class="my-auto form-group position-relative">
                                    <label for="pageOffice">Per Page:</label>
                                    <select class="form-control" wire:model="pageOffice" id="pageOffice">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
    
                            <div class="hstack justify-content-center gap-2 mt-2 col-12 col-md-6 order-md-1 order-last">
                                <div class="ms-md-auto my-auto form-group position-relative has-icon-right">
                                    <input type="text" class="form-control" placeholder="Search.." wire:model="searchoffice">
                                    <div class="form-control-icon">
                                        <i class="bi bi-search"></i>
                                    </div>
                                </div>
                                <button type="button" class="btn icon btn-primary" data-bs-toggle="modal"
                                    wire:click="select('{{ 'office' }}')" data-bs-target="#AddOfficeModal"
                                    title="Add Office">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-lg text-center">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>OFFICE NAME</th>
                                            <th>OFFICE ABBREVIATION</th>
                                            <th>HEAD OFFICE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($offices as $office)
                                            <tr>
                                                <td>{{ $office->id }}</td>
                                                <td>{{ $office->office_name }}</td>
                                                <td>{{ $office->office_abbr }}</td>
                                                <td>{{ $office->parent ? $office->parent->office_name : '' }}</td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-center">
                                                        <button type="button" class="btn icon btn-success"
                                                            wire:click="select('{{ 'office' }}', {{ $office->id }}, '{{ 'edit' }}')"
                                                            data-bs-toggle="modal" data-bs-target="#EditOfficeModal">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button type="button" class="btn icon btn-danger"
                                                            wire:click="select('{{ 'office' }}', {{ $office->id }})"
                                                            data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No record available!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer" id="account_types">
                {{ $offices->links('components.pagination') }}
            </div>
        </div>

        <div class="card">
            <div class="accordion accordion-flush card-header" id="account_typeAccordion">
                <div class="accordion-item">
                    <div class="accordion-header hstack gap-2" id="flush-headingOne" wire:ignore.self>
                        <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#account_type"
                            wire:ignore.self aria-expanded="false" aria-controls="account_type" role="button">
                            <h4>Account Types</h4>
                        </div>
                    </div>
                    <div id="account_type" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne"
                        wire:ignore.self data-bs-parent="#account_typeAccordion">
                        <div class="acordion-header mt-2 row">
                            <div class="hstack justify-content-center  justify-content-md-start col-12 col-md-6 gap-5 order-md-1 order-last">
                                <div class="my-auto form-group position-relative">
                                    <label for="ascAccType">Order By:</label>
                                    <select class="form-control" wire:model="ascAccType" id="ascAccType">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>
                                    </select>
                                </div>
                                <div class="my-auto form-group position-relative">
                                    <label for="sortAccType">Sort By:</label>
                                    <select class="form-control" wire:model="sortAccType" id="sortAccType">
                                        <option value="id">ID</option>
                                        <option value="account_type">Account Type</option>
                                    </select>
                                </div>
                                <div class="my-auto form-group position-relative">
                                    <label for="pageAccType">Per Page:</label>
                                    <select class="form-control" wire:model="pageAccType" id="pageAccType">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
    
                            <div class="hstack justify-content-center gap-2 mt-2 col-12 col-md-6 order-md-1 order-last">
                                <div class="ms-md-auto my-auto form-group position-relative has-icon-right">
                                    <input type="text" class="form-control" placeholder="Search.." wire:model="searchacctype">
                                    <div class="form-control-icon">
                                        <i class="bi bi-search"></i>
                                    </div>
                                </div>
                                <button type="button" class="btn icon btn-primary" data-bs-toggle="modal"
                                    wire:click="select('{{ 'account_type' }}')" data-bs-target="#AddAccountTypeModal"
                                    title="Add Account Type">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-lg text-center">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>NAME</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($account_types as $account_type)
                                            <tr>
                                                <td>{{ $account_type->id }}</td>
                                                <td>{{ $account_type->account_type }}</td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-center">
                                                        <button type="button" class="btn icon btn-success"
                                                            wire:click="select('{{ 'account_type' }}', {{ $account_type->id }}, '{{ 'edit' }}')"
                                                            data-bs-toggle="modal" data-bs-target="#EditAccountTypeModal">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button type="button" class="btn icon btn-danger"
                                                            wire:click="select('{{ 'account_type' }}', {{ $account_type->id }})"
                                                            data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No record available!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {{ $account_types->links('components.pagination') }}
            </div>
        </div>
    </section>

    @php
        $parentId = $parent_id;
    @endphp
    <x-modals :offices="$allOffices" :parentId="$parentId" />
</div>
