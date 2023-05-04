<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Recommended for Training</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Recommended for Training</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section pt-3">
        <div class="card collapse-icon accordion-icon-rotate">
            <div class="card-header">
                <h4 class="card-title pl-1">Personnels</h4>
            </div>
            <div class="card-body">
                @foreach ($arrays as $array)
                    <div class="accordion" id="cardAccordion">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading">
                                    <button wire:ignore.self class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                        <div class="hstack gap-2">
                                            <div class="avatar avatar-sm">
                                                {{-- <img src="{{ asset('/images/faces/1.jpg') }}"> --}}
                                                <img src="{{ $array['user']->profile_photo_url }}">
                                            </div>
                                            {{ $array['user']->name }}
                                        </div>
                                    </button>
                                </h2>
                                <div wire:ignore.self id="collapse" class="accordion-collapse collapse" aria-labelledby="heading" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body w-100">
                                        <div class="row mt-3 table-responsive"> 
                                            <table class="table table-lg text-center">
                                                <thead>
                                                    <tr>
                                                        <th>SUCCESS INDICATORS</th>
                                                        <th>AVERAGE</th>
                                                        <th>SCORE EQUIVALENT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($array['targets'] as $target)
                                                        <tr>
                                                            <td>{{ $target->target }}</td>
                                                            @php
                                                                $rating = $array['user']->targets()->where('id', $target->id)->first()->ratings()->where('user_id', $array['user']->id)->first();
                                                            @endphp
                                                            <td class="text-nowrap">{{ $rating->average }}</td>
                                                            <td>
                                                                @if ($rating->average >= $scoreEquivalent->out_from && $rating->average <= $scoreEquivalent->out_to)
                                                                    Outstanding
                                                                @elseif ($rating->average >= $scoreEquivalent->verysat_from && $rating->average <= $scoreEquivalent->verysat_to)
                                                                    Very Satisfactory
                                                                @elseif ($rating->average >= $scoreEquivalent->sat_from && $rating->average <= $scoreEquivalent->sat_to)
                                                                    Satisfactory
                                                                @elseif ($rating->average >= $scoreEquivalent->unsat_from && $rating->average <= $scoreEquivalent->unsat_to)
                                                                    Unsatisfactory
                                                                @elseif ($rating->average >= $scoreEquivalent->poor_from && $rating->average <= $scoreEquivalent->poor_to)
                                                                    Poor
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-3 table-responsive"> 
                                            <table class="table table-lg text-center">
                                                <thead>
                                                    <tr>
                                                        <th>TRAININGS</th>
                                                        <th>LINKS</th>
                                                        <th>KEYWORDS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($array['trainings'] as $training)
                                                        <tr>
                                                            <td>{{ $training->training_name }}</td>
                                                            <td>
                                                                @foreach (explode("\n", $training->links) as $link)
                                                                    <a href="{{ $link }}" target="_blank">{{ $link }}</a><br />
                                                                @endforeach
                                                            </td>
                                                            <td>{{ $training->keywords }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <th colspan="3">There is no available training for her/his success indicator.</th>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>