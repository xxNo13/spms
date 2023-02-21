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

    @php
        $head = false;
    @endphp
    <section class="section pt-3">
        <div class="col-12 text-end my-3">
            @foreach (auth()->user()->offices as $office)
                @if ($office->pivot->isHead)
                    @if ($duration && $duration->end_date >= date('Y-m-d'))
                        <button type="button" class="btn icon btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#AddTTMAModal" wire:click="select('assign')">
                            Add Assignment
                        </button>
                        @if ($duration)
                            <a href="{{ route('print.ttma') }}" target="_blank" class="btn icon btn-primary" title="Print TTMA">
                                <i class="bi bi-printer"></i>
                            </a>
                        @endif
                    @endif
                    @php
                        $head = true;
                    @endphp
                    @break
                @endif
            @endforeach
        </div>
        <div class="my-3">
            <div class="hstack gap-3">
                @if ($head) 
                    <div class="my-auto">
                        <input type="radio" class="btn-check" name="options" id="receive" wire:model="filter" value="receive">
                        <label class="btn btn-outline-primary" for="receive">Received Assignment</label>

                        <input type="radio" class="btn-check" name="options" id="give" wire:model="filter" value="give">
                        <label class="btn btn-outline-primary" for="give">Given Assignment</label>
                    </div>
                @endif
                <div class="ms-auto my-auto form-group position-relative has-icon-right">
                    <input type="text" class="form-control" placeholder="Search.." wire:model="search">
                    <div class="form-control-icon">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>
        </div>

        @if ($filter == 'give')
            <div class="card collapse-icon accordion-icon-rotate">
                <div class="card-header">
                <h4 class="card-title pl-1">Given Assignments</h4>
                </div>
                <div class="card-body">
                    @foreach ($ttmas as $ttma) 
                        <div class="accordion" id="cardAccordion">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{$ttma->id}}">
                                        <button wire:ignore.self class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $ttma->id }}" aria-expanded="false" aria-controls="collapse{{ $ttma->id }}">
                                            Task No. {{ $ttma->id }} - {{ $ttma->user_id == auth()->user()->id ? $ttma->head->name : $ttma->user->name }}
                                            <span class="ms-auto hstack gap-2">
                                                @if ($ttma->remarks == 'Done') 
                                                    <div class="rounded-pill bg-primary p-2 text-white">Done</div>
                                                    @if ($ttma->deadline < date('Y-m-d', strtotime($ttma->updated_at)))
                                                        <div class="rounded-pill bg-danger p-2 text-white">Late</div>
                                                    @endif
                                                @elseif (!$ttma->remarks && $ttma->deadline < date('Y-m-d'))
                                                    <div class="rounded-pill bg-danger p-2 text-white">Undone</div>
                                                @else
                                                    <div class="rounded-pill bg-warning p-2 text-dark">Working</div>
                                                @endif
                                            </span>
                                        </button>
                                    </h2>
                                    <div wire:ignore.self id="collapse{{ $ttma->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{$ttma->id}}" data-bs-parent="#accordionExample" style="">
                                        <div class="accordion-body w-100">
                                            <div class="row">
                                                <div class="col-6">
                                                    {{ $ttma->subject }} - {{ $ttma->output }}
                                                </div>
                                                <div class="col-6 text-end">
                                                    Deadline: {{ date('M d, Y', strtotime($ttma->deadline)) }}
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-2 mt-auto mx-auto mb-2">
                                                    @if (!$ttma->remarks && $ttma->head_id == auth()->user()->id)
                                                        <div class="mb-2">
                                                            <button type="button" class="btn icon btn-outline-success"
                                                                data-bs-toggle="modal" data-bs-target="#EditTTMAModal"
                                                                wire:click="select('assign', {{ $ttma->id }}, '{{ 'edit' }}')">
                                                                Edit
                                                            </button>
                                                            <button type="button" class="btn icon btn-outline-danger"
                                                                data-bs-toggle="modal" data-bs-target="#DeleteModal"
                                                                wire:click="select('assign',{{ $ttma->id }})">
                                                                Delete
                                                            </button>
                                                        </div>
                                                        <button type="button" class="btn icon btn-outline-info" data-bs-toggle="modal" data-bs-target="#DoneModal" wire:click="select('assign', {{ $ttma->id }})">
                                                            Mark as Done
                                                        </button>
                                                    @endif
                                                </div>  
                                                <div class="col-10 ms-auto bg-light p-2 rounded">
                                                    <h6>Messages:</h6>
                                                    <hr>
                                                    <div wire:poll class="overflow-auto" style="height: 225px;">
                                                        @foreach ($ttma->messages as $message) 
                                                            @if ($message->user_id == auth()->user()->id) 
                                                                <div class="my-3 ms-auto rounded text-white bg-primary p-2" style="width: fit-content; max-width: 80%;">
                                                                    {{ $message->message }}
                                                                </div>
                                                            @else
                                                                <div class="my-3 rounded text-white bg-secondary p-2" style="width: fit-content; max-width: 80%;">
                                                                    {{ $message->message }}
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <hr>
                                                    <form wire:submit.prevent="message" class="col-12 hstack gap-2">
                                                        <input type="text" class="form-control" wire:model="message" {{ $ttma->remarks ? 'disabled' : '' }}>
                                                        <button class="btn btn-primary" wire:click="select('message', {{ $ttma->id }})"  {{ $ttma->remarks ? 'disabled' : '' }}>Send</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif ($filter == 'receive')
            <div class="card collapse-icon accordion-icon-rotate">
                <div class="card-header">
                <h4 class="card-title pl-1">Revieved Assignments</h4>
                </div>
                <div class="card-body">
                    @foreach ($assignments as $ttma) 
                        <div class="accordion" id="cardAccordion">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{$ttma->id}}">
                                        <button wire:ignore.self class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $ttma->id }}" aria-expanded="false" aria-controls="collapse{{ $ttma->id }}">
                                            Task No. {{ $ttma->id }} - {{ $ttma->user_id == auth()->user()->id ? $ttma->head->name : $ttma->user->name }}
                                            <span class="ms-auto hstack gap-2">
                                                @if ($ttma->remarks == 'Done') 
                                                    <div class="rounded-pill bg-primary p-2 text-white">Done</div>
                                                    @if ($ttma->deadline < date('Y-m-d', strtotime($ttma->updated_at)))
                                                        <div class="rounded-pill bg-danger p-2 text-white">Late</div>
                                                    @endif
                                                @elseif (!$ttma->remarks && $ttma->deadline < date('Y-m-d'))
                                                    <div class="rounded-pill bg-danger p-2 text-white">Undone</div>
                                                @else
                                                    <div class="rounded-pill bg-warning p-2 text-dark">Working</div>
                                                @endif
                                            </span>
                                        </button>
                                    </h2>
                                    <div wire:ignore.self id="collapse{{ $ttma->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{$ttma->id}}" data-bs-parent="#accordionExample" style="">
                                        <div class="accordion-body w-100">
                                            <div class="row">
                                                <div class="col-6">
                                                    {{ $ttma->subject }} - {{ $ttma->output }}
                                                </div>
                                                <div class="col-6 text-end">
                                                    Deadline: <span class="{{ (!$ttma->remarks && $ttma->deadline < date('Y-m-d')) ? 'text-danger' : '' }}">{{ date('M d, Y', strtotime($ttma->deadline)) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-2 mt-auto mb-2">
                                                    @if (!$ttma->remarks && $ttma->head_id == auth()->user()->id)
                                                        <button type="button" class="btn icon btn-outline-info" data-bs-toggle="modal" data-bs-target="#DoneModal" wire:click="select('assign', {{ $ttma->id }})">
                                                            Mark as Done
                                                        </button>
                                                    @endif
                                                </div>  
                                                <div class="col-10 ms-auto bg-light p-2 rounded">
                                                    <h6>Messages:</h6>
                                                    <hr>
                                                    <div wire:poll class="overflow-auto" style="height: 225px;">
                                                        @foreach ($ttma->messages as $message) 
                                                            @if ($message->user_id == auth()->user()->id) 
                                                                <div class="my-3 ms-auto rounded text-white bg-primary p-2" style="width: fit-content; max-width: 80%;">
                                                                    {{ $message->message }}
                                                                </div>
                                                            @else
                                                                <div class="my-3 rounded text-white bg-secondary p-2" style="width: fit-content; max-width: 80%;">
                                                                    {{ $message->message }}
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <hr>
                                                    <form wire:submit.prevent="message" class="col-12 hstack gap-2">
                                                        <input type="text" class="form-control" wire:model="message" {{ $ttma->remarks ? 'disabled' : '' }}>
                                                        <button class="btn btn-primary" wire:click="select('message', {{ $ttma->id }})"  {{ $ttma->remarks ? 'disabled' : '' }}>Send</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <x-modals :users="$users" />
</div>
