<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IPCR - {{ $user->name }} - Faculty</title>
    <link rel="icon" href="{{ asset('images/logo/icon.png') }}">
    <style>
        @page {
            margin: 100px 50px 110px 50px;
        }

        #header {
            position: relative;
            left: 0px;
            top: -50px;
            right: 0px;
            text-align: center;
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom:
                -100px;
            right: 0px;
            text-align: center;
        }

        * {
            font-size: 8px;
            font-family: 'Arial', 'Helvetica', sans-serif;
        }

        .top-table {
            width: 95%;
            margin: 10rem auto 4rem auto;
            border-collapse: collapse;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table td {
            border: 1px solid black;
        }

        th,
        .bordered {
            border: 1px solid black;
        }

        td,
        th {
            padding: 5px;
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .border-right {
            border-right: 1px solid black;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .iso-table {
            width: 150px;
            border-collapse: collapse;
            position: absolute;
            top: -15px;
            right: 0;
        }

        .iso-table th{
            padding-top: 0;
            padding-bottom: 0; 
        }
    </style>
</head>

<body>
    <div id="header">
        <img src="{{ public_path('images/logo/header.jpg') }}">
        <table class="iso-table">
            <tbody>
                <tr>
                    <th class="text-start">Form No.</th>
                    <th>FM-DNSC-SPE-01</th>
                </tr>
                <tr>
                    <th class="text-start">Issue Status</th>
                    <th>03</th>
                </tr>
                <tr>
                    <th class="text-start">Revision No.</th>
                    <th>04</th>
                </tr>
                <tr>
                    <th class="text-start">Effective Date</th>
                    <th>27-Jan-2022</th>
                </tr>
                <tr>
                    <th class="text-start">Approved by</th>
                    <th>President</th>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="footer">
        <img src="{{ public_path('images/logo/footer.jpg') }}">
    </div>
    <div>
        <p style="font-size: 10px;" class="text-center">
            I, <u>{{ $user->name }}</u>,
            <u><b>{{ $title }}</b></u> of 
            <u><b>{{ $office }}</b></u>, commit to deliver and agree to be rated
            on the attainment of the following targets in accordance with the indicated measures for the period {{ date('Y', strtotime($duration->end_date)) }}.
        </p>
    </div>
    <div style="margin-top: 1rem; float: right;">
        <div style="text-align: center;">
            <p>______________________</p>
            <p>(Employee's Signature)</p>
            <p>Date: <u>{{ date('m-d-Y') }}</u></p>
        </div>
    </div>
    <table class="top-table">
        <thead>
            <tr>
                <th colspan="2">Reviewed By:</th>
                <th>Date:</th>
                <th colspan="2">Approved By:</th>
                <th>Date:</th>
                <th colspan="2">Rating Legend</th>
                <th colspan="2">Scale and Description</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td colspan="2" rowspan="5" class="bordered">
                    <div class="d-flex">
                        @foreach ($approval_reviewer as $reviewer)
                            <div style="margin: 1.5rem 0;">
                                {{ $reviewer->name }} <br/> 
                                @if ($office = $reviewer->offices()->wherePivot('isHead', true)->first()) 
                                    @if ($office->getDepthAttribute() == 0)
                                        <small>(Head of Agency)</small>
                                    @elseif ($office->getDepthAttribute() == 1)
                                        <small>(Head of Delivery Unit)</small>
                                    @else
                                        <small>(Head of Office)</small>
                                    @endif
                                @endif 
                                <br/>
                            </div>
                        @endforeach
                    </div>
                </td>
                <td rowspan="5" class="bordered">
                    {{-- {{ date('M d, Y', strtotime($approval->reviewers()->orderBy('updated_at', 'DESC')->first()->pivot->review_date)) }} --}}
                </td>
                <td colspan="2" rowspan="5" class="bordered">
                    @if (isset($approval_approver->name))
                        {{ $approval_approver->name }} <br/> 
                        @if ($office = $approval_approver->offices()->wherePivot('isHead', true)->first()) 
                            @if ($office->getDepthAttribute() == 0)
                                <small>(Head of Agency)</small>
                            @elseif ($office->getDepthAttribute() == 1)
                                <small>(Head of Delivery Unit)</small>
                            @else
                                <small>(Head of Office)</small>
                            @endif
                        @endif
                    @endif 
                </td>
                <td rowspan="5" class="bordered">
                    {{-- {{ date('M d, Y', strtotime($approval->approve_date)) }} --}}
                </td>
                <td class="bold">Q</td>
                <td class="border-right">Quality</td>
                <td class="bold">5</td>
                <td class="border-right">Outstanding</td>
            </tr>
            <tr>
                <td class="bold">E</td>
                <td class="border-right">Efficiency</td>
                <td class="bold">4</td>
                <td class="border-right">Very Satisfactory</td>
            </tr>
            <tr>
                <td class="bold">T</td>
                <td class="border-right">Timliness</td>
                <td class="bold">3</td>
                <td class="border-right">Satisfactory</td>
            </tr>
            <tr>
                <td class="bold">A</td>
                <td class="border-right">Average</td>
                <td class="bold">2</td>
                <td class="border-right">Unsatisfactory</td>
            </tr>
            <tr class="border-bottom">
                <td class="bold">NR</td>
                <td class="border-right">Not Rated</td>
                <td class="bold">1</td>
                <td class="border-right">Poor</td>
            </tr>
        </tbody>
    </table>

    <table class="main-table bordered">
        @php
            $totalCF = 0;
            $totalSTF = 0;
            $totalSF = 0;
            $numberCF = 0;
            $numberSTF = 0;
            $numberSF = 0;
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
        @endphp
        @foreach ($functs as $funct)
            <tbody>
                <tr>
                    <th rowspan="2" colspan="2">
                        {{ $funct->funct }}
                        @switch(strtolower($funct->funct))
                            @case('core function')
                                {{ $percentage->core }}%
                            @break

                            @case('strategic function')
                                {{ $percentage->strategic }}%
                            @break

                            @case('support function')
                                {{ $percentage->support }}%
                            @break
                        @endswitch
                    </th>
                    <th rowspan="2">Success Indicator (Target + Measure)</th>
                    <th rowspan="2" colspan="2">Actual Accomplishment</th>
                    <th colspan="4">Rating</th>
                    <th rowspan="2">Remarks</th>
                </tr>
                <tr>
                    <th style="width: 50px;">Q</th>
                    <th style="width: 50px;">E</th>
                    <th style="width: 50px;">T</th>
                    <th style="width: 50px;">A</th>
                </tr>
                @php
                    $total = 0;
                    $number = 0;
                    $numberSubF = 0;
                @endphp
                @if ($funct->sub_functs)
                    @foreach ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                        @php
                            $total = 0;
                            $numberSubF = 0;
                        @endphp
                        <tr>
                            <td colspan="2">
                                {{ $sub_funct->sub_funct }} 
                                @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                    {{ $percent = $sub_percentage->value }}%
                                @else
                                    {{ $percent = 0 }}%
                                @endif
                            </td>
                            <td colspan="8">

                            </td>
                        </tr>
                        @foreach ($user->outputs()->where('sub_funct_id', $sub_funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $output)
                            @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $suboutput)
                                <tr>
                                    <td>
                                        {{ $output->code }} {{ ++$number }}
                                    </td>
                                    <td>
                                        {{ $output->output }}
                                    </td>
                                    <td colspan="8"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" rowspan="{{ count($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get()) }}">
                                    {{ $suboutput->suboutput }}
                                    </td>

                                    @php
                                        $first = true;
                                    @endphp
                                    @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                        @if ($first)
                                            <td>{{ $target->target }}</td>
                                            @forelse ($target->ratings as $rating)
                                                @if ($rating->user_id == $user->id)
                                                    <td colspan="2">{{ $rating->accomplishment }}</td>
                                                    <td>
                                                        @if ($rating->quality)
                                                        {{ $rating->quality }}
                                                        @else
                                                        NR
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rating->efficiency)
                                                            {{ $rating->efficiency }}
                                                        @else
                                                            NR
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rating->timeliness)
                                                            {{ $rating->timeliness }}
                                                        @else
                                                            NR
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap">{{ $rating->average }}</td>
                                                    <td>{{ $rating->remarks }}</td>
                                                    @switch($funct->funct)
                                                        @case('Core Function')
                                                            @php
                                                                $total += $rating->average;
                                                                $numberSubF++;
                                                                $numberCF++;
                                                            @endphp
                                                            @break
                                                        @case('Strategic Function')
                                                            @php
                                                                $total += $rating->average;
                                                                $numberSubF++;
                                                                $numberSTF++;
                                                            @endphp
                                                            @break
                                                        @case('Support Function')
                                                            @php
                                                                $total += $rating->average;
                                                                $numberSubF++;
                                                                $numberSF++;
                                                            @endphp
                                                            @break
                                                    @endswitch
                                                    @break;
                                                @elseif ($loop->last)
                                                    <td colspan="2"></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            @empty
                                                <td colspan="2"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endforelse
                                            @php
                                                $first = false;
                                            @endphp
                                        @else
                                            <tr>
                                                <td>{{ $target->target }}</td>
                                                @forelse ($target->ratings as $rating)
                                                    @if ($rating->user_id == $user->id)
                                                        <td colspan="2">{{ $rating->accomplishment }}</td>
                                                        <td>
                                                            @if ($rating->quality)
                                                            {{ $rating->quality }}
                                                            @else
                                                            NR
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($rating->efficiency)
                                                                {{ $rating->efficiency }}
                                                            @else
                                                                NR
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($rating->timeliness)
                                                                {{ $rating->timeliness }}
                                                            @else
                                                                NR
                                                            @endif
                                                        </td>
                                                        <td class="text-nowrap">{{ $rating->average }}</td>
                                                        <td>{{ $rating->remarks }}</td>
                                                        @switch($funct->funct)
                                                            @case('Core Function')
                                                                @php
                                                                    $total += $rating->average;
                                                                    $numberSubF++;
                                                                    $numberCF++;
                                                                @endphp
                                                                @break
                                                            @case('Strategic Function')
                                                                @php
                                                                    $total += $rating->average;
                                                                    $numberSubF++;
                                                                    $numberSTF++;
                                                                @endphp
                                                                @break
                                                            @case('Support Function')
                                                                @php
                                                                    $total += $rating->average;
                                                                    $numberSubF++;
                                                                    $numberSF++;
                                                                @endphp
                                                                @break
                                                        @endswitch
                                                        @break;
                                                    @elseif ($loop->last)
                                                        <td colspan="2"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    @endif
                                                @empty
                                                    <td colspan="2"></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endforelse
                                            </tr>
                                        @endif
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td rowspan="{{ count($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get()) }}">
                                        {{ $output->code }} {{ ++$number }}
                                    </td>
                                    <td rowspan="{{ count($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get()) }}">
                                        {{ $output->output }}
                                    </td>
    
                                    @php
                                        $first = true;
                                    @endphp
                                    @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                        @if ($first)
                                            <td>{{ $target->target }}</td>
                                            @forelse ($target->ratings as $rating)
                                                @if ($rating->user_id == $user->id)
                                                    <td colspan="2">{{ $rating->accomplishment }}</td>
                                                    <td>
                                                        @if ($rating->quality)
                                                        {{ $rating->quality }}
                                                        @else
                                                        NR
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rating->efficiency)
                                                            {{ $rating->efficiency }}
                                                        @else
                                                            NR
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rating->timeliness)
                                                            {{ $rating->timeliness }}
                                                        @else
                                                            NR
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap">{{ $rating->average }}</td>
                                                    <td>{{ $rating->remarks }}</td>
                                                    @switch($funct->funct)
                                                        @case('Core Function')
                                                            @php
                                                                $total += $rating->average;
                                                                $numberSubF++;
                                                                $numberCF++;
                                                            @endphp
                                                            @break
                                                        @case('Strategic Function')
                                                            @php
                                                                $total += $rating->average;
                                                                $numberSubF++;
                                                                $numberSTF++;
                                                            @endphp
                                                            @break
                                                        @case('Support Function')
                                                            @php
                                                                $total += $rating->average;
                                                                $numberSubF++;
                                                                $numberSF++;
                                                            @endphp
                                                            @break
                                                    @endswitch
                                                    @break;
                                                @elseif ($loop->last)
                                                    <td colspan="2"></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            @empty
                                                <td colspan="2"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endforelse
                                            @php
                                                $first = false;
                                            @endphp
                                        @else
                                            <tr>
                                                <td>{{ $target->target }}</td>
                                                @forelse ($target->ratings as $rating)
                                                    @if ($rating->user_id == $user->id)
                                                        <td colspan="2">{{ $rating->accomplishment }}</td>
                                                        <td>
                                                            @if ($rating->quality)
                                                            {{ $rating->quality }}
                                                            @else
                                                            NR
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($rating->efficiency)
                                                                {{ $rating->efficiency }}
                                                            @else
                                                                NR
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($rating->timeliness)
                                                                {{ $rating->timeliness }}
                                                            @else
                                                                NR
                                                            @endif
                                                        </td>
                                                        <td class="text-nowrap">{{ $rating->average }}</td>
                                                        <td>{{ $rating->remarks }}</td>
                                                        @switch($funct->funct)
                                                            @case('Core Function')
                                                                @php
                                                                    $total += $rating->average;
                                                                    $numberSubF++;
                                                                    $numberCF++;
                                                                @endphp
                                                                @break
                                                            @case('Strategic Function')
                                                                @php
                                                                    $total += $rating->average;
                                                                    $numberSubF++;
                                                                    $numberSTF++;
                                                                @endphp
                                                                @break
                                                            @case('Support Function')
                                                                @php
                                                                    $total += $rating->average;
                                                                    $numberSubF++;
                                                                    $numberSF++;
                                                                @endphp
                                                                @break
                                                        @endswitch
                                                        @break;
                                                    @elseif ($loop->last)
                                                        <td colspan="2"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    @endif
                                                @empty
                                                    <td colspan="2"></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endforelse
                                            </tr>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforelse
                        @endforeach
                        <tr>
                            <td colspan="8" class="text-end">Total {{ $sub_funct->sub_funct }}</td>
                            <td>{{ $total }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-end">Total / {{ $numberSubF }} x {{ $percent }}% x 
                                @switch($funct->funct)
                                    @case('Core Function')
                                        {{ $percentage->core }}
                                        @break
                                    @case('Strategic Function')
                                        {{ $percentage->strategic }}
                                        @break
                                    @case('Support Function')
                                        {{ $percentage->support }}
                                        @break
                                        
                                @endswitch
                                %</td>
                            <td>
                                @switch($funct->funct)
                                    @case('Core Function')
                                        @if ($numberSubF != 0)
                                            {{ (($total/$numberSubF)*($percent/100))*($percentage->core/100) }}
                                            @php
                                                $totalCF += (($total/$numberSubF)*($percent/100))*($percentage->core/100)
                                            @endphp
                                        @else
                                            0
                                        @endif
                                        @break
                                    @case('Strategic Function')
                                        @if ($numberSubF != 0)
                                            {{ (($total/$numberSubF)*($percent/100))*($percentage->strategic/100) }}
                                            @php
                                                $totalSTF += (($total/$numberSubF)*($percent/100))*($percentage->strategic/100)
                                            @endphp
                                        @else
                                            0
                                        @endif
                                        @break
                                    @case('Support Function')
                                        @if ($numberSubF != 0)
                                            {{ (($total/$numberSubF)*($percent/100))*($percentage->support/100) }}
                                            @php
                                                $totalSF += (($total/$numberSubF)*($percent/100))*($percentage->support/100)
                                            @endphp
                                        @else
                                            0
                                        @endif
                                        @break
                                @endswitch
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                    @switch($funct->funct)
                        @case('Core Function')
                            @if ($totalCF != 0)
                                <tr>
                                    @php
                                        $x = 0;
                                    @endphp
                                    <td colspan="8" class="text-end">
                                        Total {{ $funct->funct }} (
                                        @foreach ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                                            @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                                @if ($x)
                                                    + {{ $sub_percentage->value }}%
                                                @else
                                                    {{ $sub_percentage->value }}% 
                                                    @php
                                                        $x++;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        )
                                    </td>
                                    <td>
                                        {{ $totalCF }}
                                    </td>
                                    <td></td>
                                </tr>
                            @endif
                        @break
                        @case('Strategic Function')
                        @if ($totalSTF != 0)
                            <tr>
                                @php
                                    $x = 0;
                                @endphp
                                <td colspan="8" class="text-end">
                                    Total {{ $funct->funct }} (
                                    @foreach ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                                        @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                            @if ($x)
                                                + {{ $sub_percentage->value }}%
                                            @else
                                                {{ $sub_percentage->value }}% 
                                                @php
                                                    $x++;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    )
                                </td>
                                <td>
                                    {{ $totalSTF }}
                                </td>
                                <td></td>
                            </tr>
                            
                        @endif
                        @break
                        @case('Support Function')
                        @if ($totalSF != 0)
                            <tr>
                                @php
                                    $x = 0;
                                @endphp
                                <td colspan="8" class="text-end">
                                    Total {{ $funct->funct }} (
                                    @foreach ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                                        @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                            @if ($x)
                                                + {{ $sub_percentage->value }}%
                                            @else
                                                {{ $sub_percentage->value }}% 
                                                @php
                                                    $x++;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    )
                                </td>
                                <td>
                                    {{ $totalSF }}
                                </td>
                                <td></td>
                            </tr>
                            
                        @endif
                        @break
                    @endswitch
                @endif
                @foreach ($user->outputs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $output)
                    @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $suboutput)
                        <tr>
                            <td>
                                {{ $output->code }} {{ ++$number }}
                            </td>
                            <td>
                                {{ $output->output }}
                            </td>
                            <td colspan="8"></td>
                        </tr>
                        <tr>
                            <td colspan="2" rowspan="{{ count($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get()) }}">
                                {{ $suboutput->suboutput }}
                            </td>

                            @php
                                $first = true;
                            @endphp
                            @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                @if ($first)
                                    <td>{{ $target->target }}</td>
                                    @forelse ($target->ratings as $rating)
                                        @if ($rating->user_id == $user->id)
                                            <td colspan="2">{{ $rating->accomplishment }}</td>
                                            <td>
                                                @if ($rating->quality)
                                                {{ $rating->quality }}
                                                @else
                                                NR
                                                @endif
                                            </td>
                                            <td>
                                                @if ($rating->efficiency)
                                                    {{ $rating->efficiency }}
                                                @else
                                                    NR
                                                @endif
                                            </td>
                                            <td>
                                                @if ($rating->timeliness)
                                                    {{ $rating->timeliness }}
                                                @else
                                                    NR
                                                @endif
                                            </td>
                                            <td class="text-nowrap">{{ $rating->average }}</td>
                                            <td>{{ $rating->remarks }}</td>
                                            @switch($funct->funct)
                                                @case('Core Function')
                                                    @php
                                                        $totalCF += $rating->average;
                                                        $numberSubF++;
                                                        $numberCF++;
                                                    @endphp
                                                    @break
                                                @case('Strategic Function')
                                                    @php
                                                        $totalSTF += $rating->average;
                                                        $numberSubF++;
                                                        $numberSTF++;
                                                    @endphp
                                                    @break
                                                @case('Support Function')
                                                    @php
                                                        $totalSF += $rating->average;
                                                        $numberSubF++;
                                                        $numberSF++;
                                                    @endphp
                                                    @break
                                            @endswitch
                                            @break;
                                        @elseif ($loop->last)
                                            <td colspan="2"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endif
                                    @empty
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endforelse
                                    @php
                                        $first = false;
                                    @endphp
                                @else
                                    <tr>
                                        <td>{{ $target->target }}</td>
                                        @forelse ($target->ratings as $rating)
                                            @if ($rating->user_id == $user->id)
                                                <td colspan="2">{{ $rating->accomplishment }}</td>
                                                <td>
                                                    @if ($rating->quality)
                                                    {{ $rating->quality }}
                                                    @else
                                                    NR
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($rating->efficiency)
                                                        {{ $rating->efficiency }}
                                                    @else
                                                        NR
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($rating->timeliness)
                                                        {{ $rating->timeliness }}
                                                    @else
                                                        NR
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">{{ $rating->average }}</td>
                                                <td>{{ $rating->remarks }}</td>
                                                @switch($funct->funct)
                                                    @case('Core Function')
                                                        @php
                                                            $totalCF += $rating->average;
                                                            $numberSubF++;
                                                            $numberCF++;
                                                        @endphp
                                                        @break
                                                    @case('Strategic Function')
                                                        @php
                                                            $totalSTF += $rating->average;
                                                            $numberSubF++;
                                                            $numberSTF++;
                                                        @endphp
                                                        @break
                                                    @case('Support Function')
                                                        @php
                                                            $totalSF += $rating->average;
                                                            $numberSubF++;
                                                            $numberSF++;
                                                        @endphp
                                                        @break
                                                @endswitch
                                                @break;
                                            @elseif ($loop->last)
                                                <td colspan="2"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif
                                        @empty
                                            <td colspan="2"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endforelse
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td rowspan="{{ count($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get()) }}">
                                {{ $output->code }} {{ ++$number }}
                            </td>
                            <td rowspan="{{ count($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get()) }}">
                                {{ $output->output }}
                            </td>

                            @php
                                $first = true;
                            @endphp
                            @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                @if ($first)
                                    <td>{{ $target->target }}</td>
                                    @forelse ($target->ratings as $rating)
                                        @if ($rating->user_id == $user->id)
                                            <td colspan="2">{{ $rating->accomplishment }}</td>
                                            <td>
                                                @if ($rating->quality)
                                                {{ $rating->quality }}
                                                @else
                                                NR
                                                @endif
                                            </td>
                                            <td>
                                                @if ($rating->efficiency)
                                                    {{ $rating->efficiency }}
                                                @else
                                                    NR
                                                @endif
                                            </td>
                                            <td>
                                                @if ($rating->timeliness)
                                                    {{ $rating->timeliness }}
                                                @else
                                                    NR
                                                @endif
                                            </td>
                                            <td class="text-nowrap">{{ $rating->average }}</td>
                                            <td>{{ $rating->remarks }}</td>
                                            @switch($funct->funct)
                                                @case('Core Function')
                                                    @php
                                                        $totalCF += $rating->average;
                                                        $numberSubF++;
                                                        $numberCF++;
                                                    @endphp
                                                    @break
                                                @case('Strategic Function')
                                                    @php
                                                        $totalSTF += $rating->average;
                                                        $numberSubF++;
                                                        $numberSTF++;
                                                    @endphp
                                                    @break
                                                @case('Support Function')
                                                    @php
                                                        $totalSF += $rating->average;
                                                        $numberSubF++;
                                                        $numberSF++;
                                                    @endphp
                                                    @break
                                            @endswitch
                                            @break;
                                        @elseif ($loop->last)
                                            <td colspan="2"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endif
                                    @empty
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endforelse
                                    @php
                                        $first = false;
                                    @endphp
                                @else
                                    <tr>
                                        <td>{{ $target->target }}</td>
                                        @forelse ($target->ratings as $rating)
                                            @if ($rating->user_id == $user->id)
                                                <td colspan="2">{{ $rating->accomplishment }}</td>
                                                <td>
                                                    @if ($rating->quality)
                                                    {{ $rating->quality }}
                                                    @else
                                                    NR
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($rating->efficiency)
                                                        {{ $rating->efficiency }}
                                                    @else
                                                        NR
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($rating->timeliness)
                                                        {{ $rating->timeliness }}
                                                    @else
                                                        NR
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">{{ $rating->average }}</td>
                                                <td>{{ $rating->remarks }}</td>
                                                @switch($funct->funct)
                                                    @case('Core Function')
                                                        @php
                                                            $totalCF += $rating->average;
                                                            $numberSubF++;
                                                            $numberCF++;
                                                        @endphp
                                                        @break
                                                    @case('Strategic Function')
                                                        @php
                                                            $totalSTF += $rating->average;
                                                            $numberSubF++;
                                                            $numberSTF++;
                                                        @endphp
                                                        @break
                                                    @case('Support Function')
                                                        @php
                                                            $totalSF += $rating->average;
                                                            $numberSubF++;
                                                            $numberSF++;
                                                        @endphp
                                                        @break
                                                @endswitch
                                                @break;
                                            @elseif ($loop->last)
                                                <td colspan="2"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif
                                        @empty
                                            <td colspan="2"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endforelse
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @endforelse
                @endforeach
                
                @switch($funct->funct)
                    @case('Core Function')
                        <tr>
                            <td colspan="8" class="text-end">
                                Total {{ $funct->funct }}
                            </td>
                            <td>{{ $totalCF }}</td>
                            <td></td>
                        </tr>
                        @break
                    @case('Strategic Function')
                        <tr>
                            <td colspan="8" class="text-end">
                                Total {{ $funct->funct }}
                            </td>
                            <td>{{ $totalSTF }}</td>
                            <td></td>
                        </tr>
                        @break
                    @case('Support Function')
                        <tr>
                            <td colspan="8" class="text-end">
                                Total {{ $funct->funct }}
                            </td>
                            <td>{{ $totalSF }}</td>
                            <td></td>
                        </tr>
                        @break
                @endswitch
            </tbody>
        @endforeach

        <tfoot>
            <tr>
                <th colspan="3">Category</th>
                <th colspan="2">Average</th>
                <th colspan="3">MFO (tot. no.)</th>
                <th>Percentage</th>
                <th>Total</th>
            </tr>
            @foreach ($functs as $funct)
                @if ($funct->funct == 'Core Function')
                    @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                        <tr>
                            <td colspan="3" class="text-start">{{ $funct->funct }}</td>
                            <td style="border-right: none;" colspan="2">
                                @if ($percentage->core != 0)
                                    {{ ($totalCF*$numberCF)/($percentage->core/100) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td style="border-right: none; border-left: none;">/</td>
                            <td style="border-right: none; border-left: none;">{{ $numberCF }}</td>
                            <td style="border-left: none;">X</td>
                            <td>{{ $percentage->core/100 }}</td>
                            <td>{{ $total1 = $totalCF }}</td>
                        </tr>
                        @break
                    @empty
                        <tr>
                            <td colspan="3" class="text-start">{{ $funct->funct }}</td>
                            <td style="border-right: none;" colspan="2">{{ $totalCF }}</td>
                            <td style="border-right: none; border-left: none;">/</td>
                            <td style="border-right: none; border-left: none;">{{ $numberCF }}</td>
                            <td style="border-left: none;">X</td>
                            <td>{{ $percentage->core/100 }}</td>
                            <td>
                                @if ($numberCF == 0 && $total1 == 0)
                                    {{ $total1 = 0 }}
                                @elseif ($numberCF != 0 && $total1 == 0)
                                    {{ $total1 = ($totalCF/$numberCF)*($percentage->core/100) }}
                                @endif
                            </td>
                        </tr>
                    @endforelse
                @elseif ($funct->funct == 'Strategic Function')
                    @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                        <tr>
                            <td colspan="3" class="text-start">{{ $funct->funct }}</td>
                            <td style="border-right: none;" colspan="2">
                                @if ($percentage->strategic != 0)
                                    {{ ($totalSTF*$numberSTF)/($percentage->strategic/100) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td style="border: none;">/</td>
                            <td style="border: none;">{{ $numberSTF }}</td>
                            <td style="border-left: none;">X</td>
                            <td>{{ $percentage->strategic/100 }}</td>
                            <td>{{ $total2 = $totalSTF }}</td>
                        </tr>
                        @break
                    @empty
                        <tr>
                            <td colspan="3" class="text-start">{{ $funct->funct }}</td>
                            <td style="border-right: none;" colspan="2">{{ $totalSTF }}</td>
                            <td style="border: none;">/</td>
                            <td style="border: none;">{{ $numberSTF }}</td>
                            <td style="border-left: none;">X</td>
                            <td>{{ $percentage->strategic/100 }}</td>
                            <td>
                                @if ($numberSTF == 0 && $total2 == 0)
                                    {{ $total2 = 0 }}
                                @elseif ($numberSTF != 0 && $total2 == 0)
                                    {{ $total2 = ($totalSTF/$numberSTF)*($percentage->strategic/100) }}
                                @endif
                            </td>
                        </tr>
                    @endforelse
                @elseif ($funct->funct == 'Support Function')
                    @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                        <tr>
                            <td colspan="3" class="text-start">{{ $funct->funct }}</td>
                            <td style="border-right: none;" colspan="2">
                                @if ($percentage->support != 0)
                                    {{ ($totalSF*$numberSF)/($percentage->support/100) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td style="border-right: none; border-left: none;">/</td>
                            <td style="border-right: none; border-left: none;">{{ $numberSF }}</td>
                            <td style="border-left: none;">X</td>
                            <td>{{ $percentage->support/100 }}</td>
                            <td>{{ $total3 = $totalSF }}</td>
                        </tr>
                        @break
                    @empty
                        <tr>
                            <td colspan="3" class="text-start">{{ $funct->funct }}</td>
                            <td style="border-right: none;" colspan="2">{{ $totalSF }}</td>
                            <td style="border-right: none; border-left: none;">/</td>
                            <td style="border-right: none; border-left: none;">{{ $numberSF }}</td>
                            <td style="border-left: none;">X</td>
                            <td>{{ $percentage->support/100 }}</td>
                            <td>
                                @if ($numberSF == 0 && $total3 == 0)
                                    {{ $total3 = 0 }}
                                @elseif ($numberSF != 0 && $total3 == 0)
                                    {{ $total3 = ($totalSF/$numberSF)*($percentage->support/100) }}
                                @endif
                            </td>
                        </tr>
                    @endforelse
                @endif
            @endforeach
            <tr>
                <td colspan="2"></td>
                <td colspan="7" class="text-start">Total/Final Overall Rating</td>
                <td>{{ $total = round($total1+$total2+$total3, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td colspan="7" class="text-start">Final Average Rating</td>
                <td>{{ $total = round($total1+$total2+$total3, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td colspan="7" class="text-start">Adjectival Rating</td>
                <td>
                    @if ($total >= $scoreEquivalent->out_from && $total <= $scoreEquivalent->out_to)
                        Outstanding
                    @elseif ($total >= $scoreEquivalent->verysat_from && $total <= $scoreEquivalent->verysat_to)
                        Very Satisfactory
                    @elseif ($total >= $scoreEquivalent->sat_from && $total <= $scoreEquivalent->sat_to)
                        Satisfactory
                    @elseif ($total >= $scoreEquivalent->unsat_from && $total <= $scoreEquivalent->unsat_to)
                        Unsatisfactory
                    @elseif ($total >= $scoreEquivalent->poor_from && $total <= $scoreEquivalent->poor_to)
                        Poor
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="10" class="text-start" style="height: 100px; vertical-align: top;">
                    Comment and recommendation for Development Purposes
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-start">Discussed with:</td>
                <td colspan="6" class="text-start">Assessed by:</td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><u>{{ $user->name }}</u></p>
                </td>
                <td >Date: 
                    {{-- {{ date('M d, Y') }} --}}
                </td>
                <td style="width: 200px;">
                    <p style="white-space: break-word;">I certify that I discussed my assessment of the performance with the employee.</p>
                    <div>
                        @foreach ($assess_reviewer as $reviewer)
                            <p><u>{{ $reviewer->name }}</u></p>
                            <br/>
                        @endforeach
                    </div>
                </td>
                <td style="width: 150px;">Date: 
                    {{-- {{ date('M d, Y', strtotime($assess->reviewers()->orderBy('updated_at', 'DESC')->first()->pivot->review_date)) }} --}}
                </td>
                <td colspan="3">
                    <p class="text-start">Final rating by:</p>
                    <p><u>{{ isset($assess_approver->name) ? $assess_approver->name : '' }}</u></p>
                </td>
                <td colspan="2">Date: 
                    {{-- {{ date('M d, Y', strtotime($assess->approve_date)) }} --}}
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
