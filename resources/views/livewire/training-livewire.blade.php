<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>List of Trainings</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Trainings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section pt-3">
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto"></h4>
                <div class="ms-auto hstack gap-3">
                    <div class="my-auto form-group position-relative has-icon-right">
                        <input type="text" class="form-control" placeholder="Search.." wire:model="search">
                        <div class="form-control-icon">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>
                    <button type="button" class="btn icon btn-primary" data-bs-toggle="modal"
                        data-bs-target="#AddTrainingModal">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg text-center">
                        <thead>
                            <tr>
                                <th>TRAINING NAME</th>
                                <th>LINKS</th>
                                <th>KEYWORDS</th>
                                <th>ADDED BY</th>
                                <th>POSTED</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trainings as $training)
                                <tr>
                                    <td>{{ $training->training_name }}</td>
                                    <td>
                                        @foreach (explode("\n", $training->links) as $link)
                                            <a href="{{ $link }}" target="_blank">{{ $link }}</a><br />
                                        @endforeach
                                    </td>
                                    <td>{{ $training->keywords }}</td>
                                    <td>{{ $training->user->name }}</td>
                                    <td>{{ $training->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if ($training->user_id == Auth::user()->id)
                                            <div class="hstack gap-2">
                                                <button type="button" class="btn icon btn-success"
                                                    data-bs-toggle="modal" data-bs-target="#EditTrainingModal"
                                                    wire:click="clicked({{ $training->id }}, 'edit')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn icon btn-danger"
                                                    data-bs-toggle="modal" data-bs-target="#DeleteModal"
                                                    wire:click="clicked({{ $training->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        @endif
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
    </section>
    <x-modals />
</div>