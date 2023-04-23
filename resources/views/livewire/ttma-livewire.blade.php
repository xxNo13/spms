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

    <section class="section pt-3" >
        <div class="col-2 text-start">
            <label>Filter: </label>
            <select wire:model="month_filter" class="form-control">
                <option value="">None</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="col-12 text-end my-3 hstack">
            @foreach (auth()->user()->offices as $office)
                @if ($office->pivot->isHead)
                    @if ($duration && $duration->end_date >= date('Y-m-d'))
                        <div wire:ignore class="my-auto">
                            <input type="radio" class="btn-check" name="options" id="receive" wire:model="filter" value="receive">
                            <label class="btn btn-outline-primary" for="receive">Received Assignment</label>

                            <input type="radio" class="btn-check" name="options" id="give" wire:model="filter" value="give">
                            <label class="btn btn-outline-primary" for="give">Given Assignment</label>
                        </div>
                        <button type="button" class="ms-auto btn icon btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#AddTTMAModal" wire:click="select('assign')">
                            Add Assignment
                        </button>
                        @if ($duration)
                            <a href="{{ route('print.ttma') }}" target="_blank" class="btn icon btn-primary" title="Print TTMA">
                                <i class="bi bi-printer"></i>
                            </a>
                        @endif
                    @endif
                    @break
                @endif
            @endforeach
        </div>
        <div class="my-3">
            <div class="hstack gap-3">
                {{-- <div class="ms-auto my-auto form-group position-relative has-icon-right">
                    <input type="text" class="form-control" placeholder="Search.." wire:model="search">
                    <div class="form-control-icon">
                        <i class="bi bi-search"></i>
                    </div>
                </div> --}}
            </div>
        </div>

        @if ($filter == 'give')
            <div wire:ignore.self class="card collapse-icon accordion-icon-rotate">
                <div class="card-header">
                <h4 class="card-title pl-1">Given Assignments</h4>
                </div>
                <div class="card-body">
                    @foreach ($ttmas as $ttma)
                        @if ($month_filter == '' || ($month_filter != "" && date('m', strtotime($ttma->deadline)) == $month_filter))
                            <div class="accordion" id="cardAccordion" wire:key="givenAss{{$ttma->id}}">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{$ttma->id}}">
                                            <button wire:ignore.self class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $ttma->id }}" aria-expanded="false" aria-controls="collapse{{ $ttma->id }}">
                                                <span class="fw-bold">Task No. {{ $ttma->id }}</span> -
                                                @if ($ttma->head_id == auth()->user()->id)
                                                    @if (count($ttma->users) > 2)
                                                        {{ $ttma->users()->first()->name }}, etc.
                                                    @else
                                                        {{ $ttma->users()->first()->name }}
                                                    @endif
                                                @else
                                                    {{ $ttma->head->name }}
                                                @endif
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
                                                        <h4>Subject: {{ $ttma->subject }}</h4>
                                                        <h5>Output: {{ $ttma->output }}</h5>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <span class="fw-bold">Date Assigned:</span>{{ date('M d, Y', strtotime($ttma->created_at)) }}</span><br>
                                                        <span class="fw-bold">Deadline:</span> <span class="{{ (!$ttma->remarks && $ttma->deadline < date('Y-m-d')) ? 'text-danger' : '' }}">{{ date('M d, Y', strtotime($ttma->deadline)) }}</span><br>
                                                        @if ($ttma->remarks) <span class="fw-bold">Date Accomplished:</span> {{ date('M d, Y', strtotime($ttma->updated_at)) }} @endif
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
                                                            <livewire:message-ttma-livewire wire:key="given{{$ttma->id}}" :ttma="$ttma" />
                                                        <hr>
                                                        <form wire:submit.prevent="message">
                                                            <div class="accordion accordion-flush">
                                                                <div class="row">
                                                                    <div class="col-12 hstack align-items-center gap-2">
                                                                        <input type="text" class="form-control" wire:model.defer="message" {{ $ttma->remarks ? 'disabled' : '' }}>
                                                                        <button class="file-accordion h-100 rounded bg-primary text-white p-2 accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fileAccordion" aria-expanded="true" aria-controls="fileAccordion">
                                                                            <i class="bi bi-paperclip"></i>
                                                                        </button>
                                                                        <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="file" wire:click="select('message', {{ $ttma->id }})"  {{ $ttma->remarks ? 'disabled' : '' }}>Send</button>
                                                                    </div>
                                                                </div>
                                                                <div wire:ignore.self id="fileAccordion" class="accordion-collapse collapse" aria-labelledby="fileAccordionHeading">
                                                                    <div class="accordion-body">
                                                                        <input id="itteration.{{ $itteration }}" class="form-control" type="file" wire:model="file" accept=".pdf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @elseif ($filter == 'receive')
            <div wire:ignore.self class="card collapse-icon accordion-icon-rotate">
                <div class="card-header">
                <h4 class="card-title pl-1">Received Assignments</h4>
                </div>
                <div class="card-body">
                    @foreach ($assignments as $ttma) 
                        @if ($month_filter == '' || ($month_filter != "" && date('m', strtotime($ttma->deadline)) == $month_filter))
                            <div class="accordion" id="cardAccordion" wire:key="givenAss{{$ttma->id}}">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{$ttma->id}}">
                                            <button wire:ignore.self class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $ttma->id }}" aria-expanded="false" aria-controls="collapse{{ $ttma->id }}">
                                                <span class="fw-bold">Task No. {{ $ttma->id }}</span> - 
                                                @if ($ttma->head_id == auth()->user()->id)
                                                    @if (count($ttma->users) > 2)
                                                        {{ $ttma->users()->first()->name }}, etc.
                                                    @else
                                                        {{ $ttma->users()->first()->name }}
                                                    @endif
                                                @else
                                                    {{ $ttma->head->name }}
                                                @endif
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
                                                        <h4>Subject: {{ $ttma->subject }}</h4>
                                                        <h5>Output: {{ $ttma->output }}</h5>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <span class="fw-bold">Date Assigned:</span>{{ date('M d, Y', strtotime($ttma->created_at)) }}</span><br>
                                                        <span class="fw-bold">Deadline:</span> <span class="{{ (!$ttma->remarks && $ttma->deadline < date('Y-m-d')) ? 'text-danger' : '' }}">{{ date('M d, Y', strtotime($ttma->deadline)) }}</span><br>
                                                        @if ($ttma->remarks) <span class="fw-bold">Date Accomplished:</span> {{ date('M d, Y', strtotime($ttma->updated_at)) }} @endif
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
                                                            <livewire:message-ttma-livewire wire:key="assign{{$ttma->id}}" wire:key="assign{{$ttma->id}}" :ttma="$ttma" />
                                                        <hr>
                                                        <form wire:submit.prevent="message">
                                                            <div class="accordion accordion-flush">
                                                                <div class="row">
                                                                    <div class="col-12 hstack align-items-center gap-2">
                                                                        <input type="text" class="form-control" wire:model.defer="message" {{ $ttma->remarks ? 'disabled' : '' }}>
                                                                        <button class="file-accordion h-100 rounded bg-primary text-white p-2 accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fileAccordion" aria-expanded="true" aria-controls="fileAccordion">
                                                                            <i class="bi bi-paperclip"></i>
                                                                        </button>
                                                                        <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="file" wire:click="select('message', {{ $ttma->id }})"  {{ $ttma->remarks ? 'disabled' : '' }}>Send</button>
                                                                    </div>
                                                                </div>
                                                                <div wire:ignore.self id="fileAccordion" class="accordion-collapse collapse" aria-labelledby="fileAccordionHeading">
                                                                    <div class="accordion-body">
                                                                        <input id="itteration.{{ $itteration }}" class="form-control" type="file" wire:model="file" accept=".pdf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <x-modals :users="$users" />
</div>
