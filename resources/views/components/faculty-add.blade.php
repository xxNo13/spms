<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Individual Performance Commitment and Review</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a
                                href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">IPCR - Faculty</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="pt-3">
        <div class="col-12 hstack">
            <button wire:click="getIpcr" wire:loading.attr="disabled" wire:target="targetsSelected" class="ms-auto btn btn-outline-primary">
                Save
            </button>
        </div>
        @foreach ($functs as $funct)
            @php
                $number = 1;
            @endphp
            <div class="">
                <div class="hstack mb-3 gap-2">
                    <h4>
                        {{ $funct->funct }}
                    </h4>
                </div>
                @foreach ($funct->sub_functs()->where('funct_id', $funct->id)->where('user_type', 'faculty')->where('type', 'ipcr')->where('duration_id', $duration->id)->get() as $sub_funct)
                    <div>
                        <h5>
                            {{ $sub_funct->sub_funct }}
                        </h5>

                        @foreach ($sub_funct->outputs()->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $output)
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{ $output->code }} {{ $number++ }} - {{ $output->output }}
                                    </h4>
                                    <p class="text-subtitle text-muted"></p>
                                </div>

                                @forelse ($output->suboutputs as $suboutput)
                                    <div class="card-body">
                                        <h6>
                                            {{ $suboutput->suboutput }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-sm-flex">
                                            @foreach ($suboutput->targets as $target)
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="{{ $target->target }}{{ $target->id }}"
                                                        wire:model="targetsSelected.{{ $target->id }}" value="{{ $target->id }}" @if ($target->required)
                                                            disabled checked
                                                        @endif>
                                                    <label class="form-check-label"
                                                        for="{{ $target->target }}{{ $target->id }}">{{ $target->target }}</label>
                                                </div> 
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="card-body">
                                        <div class="d-sm-flex">
                                            @foreach ($output->targets as $target)
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="{{ $target->target }}{{ $target->id }}"
                                                        wire:model="targetsSelected.{{ $target->id }}" value="{{ $target->id }}" @if ($target->required)
                                                            disabled checked
                                                        @endif>
                                                    <label class="form-check-label"
                                                        for="{{ $target->target }}{{ $target->id }}">{{ $target->target }}</label>
                                                </div> 
                                            @endforeach
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @endforeach
                    </div>
                    <hr>
                @endforeach
                <div>
                    @foreach ($funct->outputs()->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $output)
                        
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{ $output->code }} {{ $number++ }} - {{ $output->output }}
                                </h4>
                                <p class="text-subtitle text-muted"></p>
                            </div>

                            @forelse ($output->suboutputs as $suboutput)
                                
                                <div class="card-body">
                                    <h6>
                                        {{ $suboutput->suboutput }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-sm-flex">
                                        @foreach ($suboutput->targets as $target)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    id="{{ $target->target }}{{ $target->id }}"
                                                    wire:model="targetsSelected.{{ $target->id }}" value="{{ $target->id }}" @if ($target->required)
                                                        disabled checked
                                                    @endif>
                                                <label class="form-check-label"
                                                    for="{{ $target->target }}{{ $target->id }}">{{ $target->target }}</label>
                                            </div> 
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="card-body">
                                    <div class="d-sm-flex">
                                        @foreach ($output->targets as $target)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    id="{{ $target->target }}{{ $target->id }}"
                                                    wire:model="targetsSelected.{{ $target->id }}" value="{{ $target->id }}" @if ($target->required)
                                                        disabled checked
                                                    @endif>
                                                <label class="form-check-label"
                                                    for="{{ $target->target }}{{ $target->id }}">{{ $target->target }}</label>
                                            </div> 
                                        @endforeach
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>