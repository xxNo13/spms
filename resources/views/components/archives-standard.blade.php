<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ auth()->user()->name }}</h3>
            </div>
            <div class="col-12 col-md-6 order-md-3 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('archives') }}">Archives</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span class="text-uppercase">{{ $type }}</span> Standard for {{ $duration->duration_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        <div class="row">
            <div class="col-12 hstack">
                <a href="
                    @switch($user_type)
                        @case('office')
                            {{ route('print.standard.opcr', ['id' => auth()->user()->id]) }}?duration_id={{ $duration->id }}
                            @break
                        @case('faculty')
                            {{ route('print.standard.faculty', ['id' => auth()->user()->id]) }}?duration_id={{ $duration->id }}
                            @break
                        @case('staff')
                            {{ route('print.standard.staff', ['id' => auth()->user()->id]) }}?duration_id={{ $duration->id }}
                            @break
                            
                    @endswitch
                    " target="_blank" class="ms-auto btn icon btn-primary" title="Print Standard">
                    <i class="bi bi-printer"></i>
                </a>
            </div>
        </div>
        @foreach ($functs as $funct)
            @php
                $number = 1;
            @endphp
            <div class="hstack mb-3 gap-2">
                <h4>
                    {{ $funct->funct }}
                    @if ($percentage)
                        @switch($funct->funct)
                            @case('Core Function')
                                {{ $percentage->core }}%
                                @break
                            @case('Strategic Function')
                                {{ $percentage->strategic }}%
                                @break
                            @case('Support Function')
                                {{ $percentage->support }}%
                                @break
                        @endswitch
                    @endif
                </h4>
            </div>
            @if ($funct->sub_functs)
                @foreach (auth()->user()->sub_functs()->where('type', $type)->where('user_type', $user_type)->where('duration_id', $duration->id)->where('funct_id', $funct->id)->get() as $sub_funct)
                    <div>
                        <h5>
                            {{ $sub_funct->sub_funct }}
                            @if (isset($sub_percentages))
                                @if ($sub_percentage = $sub_percentages->where('sub_funct_id', $sub_funct->id)->first())
                                    {{ $sub_percentage->value }}%
                                @endif
                            @else
                                @if ($sub_percentage = auth()->user()->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                    {{ $sub_percentage->value }}%
                                @endif
                            @endif
                        </h5>
                        @foreach (auth()->user()->outputs()->where('type', $type)->where('user_type', $user_type)->where('duration_id', $duration->id)->where('sub_funct_id', $sub_funct->id)->get() as $output)
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{ $output->code }} {{ $number++ }}
                                        {{ $output->output }}
                                    </h4>
                                    <p class="text-subtitle text-muted"></p>
                                </div>
                                @forelse (auth()->user()->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                                    <div class="card-body">
                                        <h6>
                                            {{ $suboutput->suboutput }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion accordion-flush"
                                            id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                            <div class="row">
                                                @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                                    <div class="col-12 col-sm-4">
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            @foreach ($target->ratings as $rating)
                                                                @if ($rating->user_id == auth()->user()->id)
                                                                    <span class="my-auto">
                                                                        <i class="bi bi-check2"></i>
                                                                    </span>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                            {{ $target->target }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                                <div wire:ignore.self
                                                    id="{{ 'target' }}{{ $target->id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                                    <div class="accordion-body table-responsive">
                                                        <table class="table table-lg text-center">
                                                            <thead>
                                                                <tr>
                                                                    <td colspan="6">Rating</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">E</td>
                                                                    <td colspan="2">Q</td>
                                                                    <td colspan="2">T</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($target->standards as $standard)
                                                                    @if ($standard->user_id == auth()->user()->id || $standard->user_id == null) 
                                                                        <tr>
                                                                            <td>5</td>
                                                                            <td>{{ $standard->eff_5 }}
                                                                            </td>
                                                                            <td>5</td>
                                                                            <td>{{ $standard->qua_5 }}
                                                                            </td>
                                                                            <td>5</td>
                                                                            <td>{{ $standard->time_5 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td>{{ $standard->eff_4 }}
                                                                            </td>
                                                                            <td>4</td>
                                                                            <td>{{ $standard->qua_4 }}
                                                                            </td>
                                                                            <td>4</td>
                                                                            <td>{{ $standard->time_4 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td>{{ $standard->eff_3 }}
                                                                            </td>
                                                                            <td>3</td>
                                                                            <td>{{ $standard->qua_3 }}
                                                                            </td>
                                                                            <td>3</td>
                                                                            <td>{{ $standard->time_3 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>{{ $standard->eff_2 }}
                                                                            </td>
                                                                            <td>2</td>
                                                                            <td>{{ $standard->qua_2 }}
                                                                            </td>
                                                                            <td>2</td>
                                                                            <td>{{ $standard->time_2 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>{{ $standard->eff_1 }}
                                                                            </td>
                                                                            <td>1</td>
                                                                            <td>{{ $standard->qua_1 }}
                                                                            </td>
                                                                            <td>1</td>
                                                                            <td>{{ $standard->time_1 }}
                                                                            </td>
                                                                        </tr>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="card-body">
                                        <div class="accordion accordion-flush"
                                            id="{{ 'output' }}{{ $output->id }}">
                                            <div class="row">
                                                @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)
                                                    <div class="col-12 col-sm-4">
                                                        <div wire:ignore.self
                                                            class="accordion-button collapsed gap-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="{{ 'target' }}{{ $target->id }}"
                                                            role="button">
                                                            @foreach ($target->ratings as $rating)
                                                                @if ($rating->user_id == auth()->user()->id)
                                                                    <span class="my-auto">
                                                                        <i class="bi bi-check2"></i>
                                                                    </span>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                            {{ $target->target }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)
                                                <div wire:ignore.self
                                                    id="{{ 'target' }}{{ $target->id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                                    <div class="accordion-body table-responsive">
                                                        <table class="table table-lg text-center">
                                                            <thead>
                                                                <tr>
                                                                    <td colspan="6">Rating</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">E</td>
                                                                    <td colspan="2">Q</td>
                                                                    <td colspan="2">T</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($target->standards as $standard)
                                                                    @if ($standard->user_id == auth()->user()->id || $standard->user_id == null) 
                                                                        <tr>
                                                                            <td>5</td>
                                                                            <td>{{ $standard->eff_5 }}
                                                                            </td>
                                                                            <td>5</td>
                                                                            <td>{{ $standard->qua_5 }}
                                                                            </td>
                                                                            <td>5</td>
                                                                            <td>{{ $standard->time_5 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td>{{ $standard->eff_4 }}
                                                                            </td>
                                                                            <td>4</td>
                                                                            <td>{{ $standard->qua_4 }}
                                                                            </td>
                                                                            <td>4</td>
                                                                            <td>{{ $standard->time_4 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td>{{ $standard->eff_3 }}
                                                                            </td>
                                                                            <td>3</td>
                                                                            <td>{{ $standard->qua_3 }}
                                                                            </td>
                                                                            <td>3</td>
                                                                            <td>{{ $standard->time_3 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>{{ $standard->eff_2 }}
                                                                            </td>
                                                                            <td>2</td>
                                                                            <td>{{ $standard->qua_2 }}
                                                                            </td>
                                                                            <td>2</td>
                                                                            <td>{{ $standard->time_2 }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>{{ $standard->eff_1 }}
                                                                            </td>
                                                                            <td>1</td>
                                                                            <td>{{ $standard->qua_1 }}
                                                                            </td>
                                                                            <td>1</td>
                                                                            <td>{{ $standard->time_1 }}
                                                                            </td>
                                                                        </tr>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
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
            @endif
            @foreach (auth()->user()->outputs()->where('type', $type)->where('user_type', $user_type)->where('duration_id', $duration->id)->where('funct_id', $funct->id)->get() as $output)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ $output->code }} {{ $number++ }} {{ $output->output }}
                        </h4>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    @forelse (auth()->user()->suboutputs()->where('output_id', $output->id)->get() as $suboutput)
                        <div class="card-body">
                            <h6>
                                {{ $suboutput->suboutput }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="accordion accordion-flush"
                                id="{{ 'suboutput' }}{{ $suboutput->id }}">
                                <div class="row">
                                    @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                        <div class="col-12 col-sm-4">
                                            <div wire:ignore.self class="accordion-button collapsed gap-2"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                aria-expanded="true"
                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                role="button">
                                                @foreach ($target->ratings as $rating)
                                                    @if ($rating->user_id == auth()->user()->id)
                                                        <span class="my-auto">
                                                            <i class="bi bi-check2"></i>
                                                        </span>
                                                        @break
                                                    @endif
                                                @endforeach
                                                {{ $target->target }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @foreach (auth()->user()->targets()->where('suboutput_id', $suboutput->id)->get() as $target)
                                    <div wire:ignore.self
                                        id="{{ 'target' }}{{ $target->id }}"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="flush-headingOne"
                                        data-bs-parent="#{{ 'suboutput' }}{{ $suboutput->id }}">
                                        <div class="accordion-body table-responsive">
                                            <table class="table table-lg text-center">
                                                <thead>
                                                    <tr>
                                                        <td colspan="6">Rating</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">E</td>
                                                        <td colspan="2">Q</td>
                                                        <td colspan="2">T</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($target->standards as $standard)
                                                        @if ($standard->user_id == auth()->user()->id || $standard->user_id == null) 
                                                            <tr>
                                                                <td>5</td>
                                                                <td>{{ $standard->eff_5 }}
                                                                </td>
                                                                <td>5</td>
                                                                <td>{{ $standard->qua_5 }}
                                                                </td>
                                                                <td>5</td>
                                                                <td>{{ $standard->time_5 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td>{{ $standard->eff_4 }}
                                                                </td>
                                                                <td>4</td>
                                                                <td>{{ $standard->qua_4 }}
                                                                </td>
                                                                <td>4</td>
                                                                <td>{{ $standard->time_4 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>{{ $standard->eff_3 }}
                                                                </td>
                                                                <td>3</td>
                                                                <td>{{ $standard->qua_3 }}
                                                                </td>
                                                                <td>3</td>
                                                                <td>{{ $standard->time_3 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>{{ $standard->eff_2 }}
                                                                </td>
                                                                <td>2</td>
                                                                <td>{{ $standard->qua_2 }}
                                                                </td>
                                                                <td>2</td>
                                                                <td>{{ $standard->time_2 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>{{ $standard->eff_1 }}
                                                                </td>
                                                                <td>1</td>
                                                                <td>{{ $standard->qua_1 }}
                                                                </td>
                                                                <td>1</td>
                                                                <td>{{ $standard->time_1 }}
                                                                </td>
                                                            </tr>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="card-body">
                            <div class="accordion accordion-flush"
                                id="{{ 'output' }}{{ $output->id }}">
                                <div class="row">
                                    @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)
                                        <div class="col-12 col-sm-4">
                                            <div wire:ignore.self class="accordion-button collapsed gap-2"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ 'target' }}{{ $target->id }}"
                                                aria-expanded="true"
                                                aria-controls="{{ 'target' }}{{ $target->id }}"
                                                role="button">
                                                @foreach ($target->ratings as $rating)
                                                    @if ($rating->user_id == auth()->user()->id)
                                                        <span class="my-auto">
                                                            <i class="bi bi-check2"></i>
                                                        </span>
                                                        @break
                                                    @endif
                                                @endforeach
                                                {{ $target->target }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @foreach (auth()->user()->targets()->where('output_id', $output->id)->get() as $target)
                                    <div wire:ignore.self
                                        id="{{ 'target' }}{{ $target->id }}"
                                        class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                        data-bs-parent="#{{ 'output' }}{{ $output->id }}">
                                        <div class="accordion-body table-responsive">
                                            <table class="table table-lg text-center">
                                                <thead>
                                                    <tr>
                                                        <td colspan="6">Rating</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">E</td>
                                                        <td colspan="2">Q</td>
                                                        <td colspan="2">T</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($target->standards as $standard)
                                                        @if ($standard->user_id == auth()->user()->id || $standard->user_id == null) 
                                                            <tr>
                                                                <td>5</td>
                                                                <td>{{ $standard->eff_5 }}
                                                                </td>
                                                                <td>5</td>
                                                                <td>{{ $standard->qua_5 }}
                                                                </td>
                                                                <td>5</td>
                                                                <td>{{ $standard->time_5 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td>{{ $standard->eff_4 }}
                                                                </td>
                                                                <td>4</td>
                                                                <td>{{ $standard->qua_4 }}
                                                                </td>
                                                                <td>4</td>
                                                                <td>{{ $standard->time_4 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>{{ $standard->eff_3 }}
                                                                </td>
                                                                <td>3</td>
                                                                <td>{{ $standard->qua_3 }}
                                                                </td>
                                                                <td>3</td>
                                                                <td>{{ $standard->time_3 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>{{ $standard->eff_2 }}
                                                                </td>
                                                                <td>2</td>
                                                                <td>{{ $standard->qua_2 }}
                                                                </td>
                                                                <td>2</td>
                                                                <td>{{ $standard->time_2 }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>{{ $standard->eff_1 }}
                                                                </td>
                                                                <td>1</td>
                                                                <td>{{ $standard->qua_1 }}
                                                                </td>
                                                                <td>1</td>
                                                                <td>{{ $standard->time_1 }}
                                                                </td>
                                                            </tr>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforelse
                </div>
            @endforeach
        @endforeach
    </section>
</div>
