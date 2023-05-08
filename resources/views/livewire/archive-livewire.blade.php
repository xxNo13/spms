<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ auth()->user()->name }}</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Archives</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto">Archives</h4>
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
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($durations as $duration)
                                @if (auth()->user()->outputs()->where('user_type', 'office')->where('duration_id', $duration->id)->first())
                                    <tr>
                                        <td>OPCR for {{ $duration->duration_name }}</td>
                                        <td>
                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                <button type="button" class="btn icon btn-secondary"
                                                    wire:click="viewed({{$duration->id}}, 'opcr', 'office')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>OPCR Standard for {{ $duration->duration_name }}</td>
                                        <td>
                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                <button type="button" class="btn icon btn-secondary"
                                                    wire:click="viewed({{$duration->id}}, 'opcr', 'office', 'standard')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if (auth()->user()->outputs()->where('user_type', 'faculty')->where('duration_id', $duration->id)->first())
                                    <tr>
                                        <td>Faculty IPCR for {{ $duration->duration_name }}</td>
                                        <td>
                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                <button type="button" class="btn icon btn-secondary"
                                                    wire:click="viewed({{$duration->id}}, 'ipcr', 'faculty')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Faculty IPCR Standard for {{ $duration->duration_name }}</td>
                                        <td>
                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                <button type="button" class="btn icon btn-secondary"
                                                    wire:click="viewed({{$duration->id}}, 'ipcr', 'faculty', 'standard')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if (auth()->user()->outputs()->where('user_type', 'staff')->where('duration_id', $duration->id)->first())
                                    <tr>
                                        <td>Staff IPCR for {{ $duration->duration_name }}</td>
                                        <td>
                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                <button type="button" class="btn icon btn-secondary"
                                                    wire:click="viewed({{$duration->id}}, 'ipcr', 'staff')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Staff IPCR Standard for {{ $duration->duration_name }}</td>
                                        <td>
                                            <div class="hstack align-items-center justify-content-center gap-2">
                                                <button type="button" class="btn icon btn-secondary"
                                                    wire:click="viewed({{$duration->id}}, 'ipcr', 'staff', 'standard')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
