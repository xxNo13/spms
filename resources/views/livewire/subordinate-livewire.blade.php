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
                        <li class="breadcrumb-item active" aria-current="page">Subordinates</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if ($duration)
        <div class="row text-center">
            <div class="col-6">
                <a href="/print/rankings" target="_blank" class="ms-auto btn icon btn-primary" title="Print Ranking IPCR">
                    <i class="bi bi-printer"></i>
                    Ranking of IPCR in your Office
                </a>
            </div>
            <div class="col-6">
                <a href="/print/rankings/opcr" target="_blank" class="ms-auto btn icon btn-primary" title="Print Ranking IPCR">
                    <i class="bi bi-printer"></i>
                    Rankings of OPCR
                </a>
            </div>
        </div>
        <div class="row text-center mt-2">
            <div class="col-6">
                <a href="/print/rankings/faculty" target="_blank" class="ms-auto btn icon btn-primary" title="Print Ranking IPCR">
                    <i class="bi bi-printer"></i>
                    Rankings of IPCR per Faculty
                </a>
            </div>
            <div class="col-6">
                <a href="/print/rankings/staff" target="_blank" class="ms-auto btn icon btn-primary" title="Print Ranking IPCR">
                    <i class="bi bi-printer"></i>
                    Rankings of IPCR per Staff
                </a>
            </div>
        </div>
    @endif

    <section class="section pt-3">
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto"></h4>
                <div class="ms-auto hstack gap-3">
                    <div class="my-auto form-group position-relative has-icon-right">
                        <input type="text" class="form-control" placeholder="Search.." wire:model="search" maxlength="25">
                        <div class="form-control-icon">
                            <i class="bi bi-search"></i>
                        </div>
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
                                <th>OFFFICE</th>
                                <th>ACCOUNT TYPE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="d-md-flex flex-column gap-3 justify-content-center">
                                            @foreach ($user->offices as $office)
                                                @if ($loop->last)
                                                    {{ $office->office_abbr }}
                                                    @break
                                                @endif
                                                {{ $office->office_abbr }} <br/> 
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-md-flex flex-column gap-3 justify-content-center">
                                            @foreach ($user->account_types as $account_type)
                                                @if ($loop->last)
                                                    {{ $account_type->account_type }}
                                                    @break
                                                @endif
                                                {{ $account_type->account_type }} <br/> 
                                            @endforeach

                                            @foreach ($user->offices as $office)
                                                @if ($office->pivot->isHead == 1)
                                                    @if ($user->account_types->first() !== null)
                                                        <br/> {{ "Head" }}
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-md-flex flex-column gap-3 justify-content-center">
                                            @if (isset($duration))
                                                @php
                                                    $faculty = false;
                                                    $staff = false;
                                                @endphp
                                                @foreach ($user->account_types as $account_type)
                                                    @if (str_contains(strtolower($account_type), 'faculty'))
                                                        @php
                                                            $faculty = true;
                                                        @endphp
                                                    @endif
                                                    @if (str_contains(strtolower($account_type), 'staff'))
                                                        @php
                                                            $staff = true;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if ($faculty)
                                                    <button type="button" class="btn icon icon-left btn-secondary" wire:click="viewed({{ $user->id }}, '{{ 'officemates' }}', '{{ 'faculty' }}')">
                                                        <i class="bi bi-eye"></i> 
                                                        IPCR (Faculty)
                                                    </button>
                                                @endif
                                                
                                                @if ($staff)
                                                    <button type="button" class="btn icon icon-left btn-secondary" wire:click="viewed({{ $user->id }}, '{{ 'officemates' }}', '{{ 'staff' }}')">
                                                        <i class="bi bi-eye"></i> 
                                                        IPCR (Staff)
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
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
    </section>

    <x-modals />
</div>
