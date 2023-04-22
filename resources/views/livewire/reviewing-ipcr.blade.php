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
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (auth()->user()->reviewing_ipcrs as $ipcr)
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
                                        <td>
                                            <div class="hstack justify-content-center align-items-center gap-3">
                                                @if ($ipcr->status)
                                                    Approved
                                                @endif
                                                <button class="btn icon btn-secondary" wire:click="viewed({{ $ipcr->id }}, 'reviewing')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
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
