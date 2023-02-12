<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Training Recommendation</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a
                                href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Training Recommendation</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section pt-3">
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto">List of Failed Success Indicators</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg text-center">
                        <thead>
                            <tr>
                                <th>OUTPUT</th>
                                <th>SUCCESS INDICATORS</th>
                                <th>AVERAGE</th>
                                <th>SCORE EQUIVALENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($targets as $target)
                                <tr>
                                    <td>{{ $target->suboutput ? $target->suboutput->output->output : $target->output->output }}</td>
                                    <td>{{ $target->target }}</td>
                                    @php
                                        $rating = auth()->user()->targets()->where('id', $target->id)->first()->ratings()->where('user_id', auth()->user()->id)->first();
                                    @endphp
                                    <td>{{ $rating->average }}</td>
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
        <div class="card">
            <div class="card-header hstack">
                <h4 class="card-title my-auto">List of Available Training</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg text-center">
                        <thead>
                            <tr>
                                <th>TRAININGS</th>
                                <th>LINKS</th>
                                <th>KEYWORDS</th>
                                <th>USER ADDED</th>
                                <th>POSTED</th>
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
                                </tr>
                                @if ($loop->last)
                                    <tr>
                                        <th colspan="6">If you can't find suitable training for your failed success indicator. Please contact either your head or the HRMO. Thank you!</th>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <th colspan="6">If you can't find suitable training for your failed success indicator. Please contact either your head or the HRMO. Thank you!</th>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <div class="hstack">
                                <div class="ms-auto">
                                    {{ $trainings->links('components.pagination') }}
                                </div>
                            </div>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
