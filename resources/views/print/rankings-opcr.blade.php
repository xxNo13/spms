<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OPCR - Ranking</title>
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
            font-family: Arial, Helvetica, sans-serif;
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
    </style>
</head>

<body>
    <div id="header">
        <img src="{{ public_path('images/logo/header.jpg') }}">
    </div>
    <div id="footer">
        <img src="{{ public_path('images/logo/footer.jpg') }}">
    </div>

    <table class="main-table bordered">
        <tbody>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Account Type</th>
                <th>Total Score</th>
                <th>Score Equivalent</th>
            </tr>
        </tbody>
        @foreach ($users as $user)
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
                @php
                    $total = 0;
                    $number = 0;
                    $numberSubF = 0;
                @endphp
                @if ($funct->sub_functs)
                    @foreach ($user->sub_functs()->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $subFunct)
                        @php
                            $total = 0;
                            $numberSubF = 0;
                        @endphp
                        @if ($sub_percentage = $sub_percentages->where('sub_funct_id', $sub_funct->id)->first())
                            @php $percent = $sub_percentage->value @endphp
                        @endif
                        @foreach ($user->outputs()->where('sub_funct_id', $sub_funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $output)
                            @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $suboutput)
                                @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                    @foreach ($target->ratings as $rating)
                                        @if ($rating->user_id == $user->id) 
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
                                            @break
                                        @endif
                                    @endforeach
                                @endforeach
                            @empty
                                @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                    @foreach ($target->ratings as $rating)
                                        @if ($rating->user_id == $user->id) 
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
                                            @break
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforelse
                        @endforeach
                        @switch($funct->funct)
                            @case('Core Function')
                                @php
                                    if ($numberSubF == 0) {
                                        $totalCF += 0;
                                        break;
                                    }
                                    $totalCF += (($total/$numberSubF)*($percent/100))*($percentage->core/100)
                                @endphp
                                @break
                            @case('Strategic Function')
                                @php
                                    if ($numberSubF == 0) {
                                        $totalSTF += 0;
                                        break;
                                    }
                                    $totalSTF += (($total/$numberSubF)*($percent/100))*($percentage->strategic/100)
                                @endphp
                                @break
                            @case('Support Function')
                                @php
                                    if ($numberSubF == 0) {
                                        $totalSF += 0;
                                        break;
                                    }
                                    $totalSF += (($total/$numberSubF)*($percent/100))*($percentage->support/100)
                                @endphp
                                @break
                        @endswitch
                    @endforeach
                @endif
                @foreach ($user->outputs()->where('funct_id', $funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $output)
                    @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $suboutput)
                        @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                            @foreach ($target->ratings as $rating)
                                @if ($rating->user_id == $user->id) 
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
                                    @break
                                @endif
                            @endforeach
                        @endforeach
                    @empty
                        @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                            @foreach ($target->ratings as $rating)
                                @if ($rating->user_id == $user->id) 
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
                                    @break
                                @endif
                            @endforeach
                        @endforeach
                    @endforelse
                @endforeach
            @endforeach
            @foreach ($functs as $funct)
                @if ($funct->funct == 'Core Function')
                    @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $sub_funct)
                        @php
                            $total1 = $totalCF
                        @endphp
                        @break
                    @empty
                        @if ($numberCF == 0 && $total1 == 0)
                            @php $total1 = 0 @endphp
                        @elseif ($numberCF != 0 && $total1 == 0)
                            @php $total1 = ($totalCF/$numberCF)*($percentage->core/100) @endphp
                        @endif
                    @endforelse
                @elseif ($funct->funct == 'Strategic Function')
                    @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $sub_funct)
                        @php
                            $total2 = $totalSTF
                        @endphp
                        @break
                    @empty
                        @if ($numberSTF == 0 && $total2 == 0)
                            @php $total2 = 0 @endphp
                        @elseif ($numberSTF != 0 && $total2 == 0)
                            @php $total2 = ($totalSTF/$numberSTF)*($percentage->strategic/100) @endphp
                        @endif
                    @endforelse
                @elseif ($funct->funct == 'Support Function')
                    @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'opcr')->where('user_type', 'office')->where('duration_id', $duration->id)->get() as $sub_funct)
                        @php
                            $total3 = $totalSF
                        @endphp
                        @break
                    @empty
                        @if ($numberSF == 0 && $total3 == 0)
                            @php $total3 = 0 @endphp
                        @elseif ($numberSF != 0 && $total3 == 0)
                            @php $total3 = ($totalSF/$numberSF)*($percentage->support/100) @endphp
                        @endif
                    @endforelse
                @endif
            @endforeach
            @php
                $totals[$user->id . ','. 'office'] = round($total1+$total2+$total3, 2);
            @endphp
        @endforeach
        @if (isset($totals))
            @php
                arsort($totals);
                $number = 0;
            @endphp
            <tfoot>
                @foreach ($totals as $id => $total)
                    @php
                        $index = explode( ',', $id );
                    @endphp
                    @foreach ($users as $user)
                        @if ($index[0] == $user->id)
                            <tr>
                                <td>{{ ++$number }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ ucfirst($index[1]) }}</td>
                                <td>{{ $totals[$user->id . ','. $index[1]] }}</td>
                                <td>
                                    @if ($totals[$user->id . ','. $index[1]] >= $scoreEquivalent->out_from && $totals[$user->id . ','. $index[1]] <= $scoreEquivalent->out_to)
                                        Outstanding
                                    @elseif ($totals[$user->id . ','. $index[1]] >= $scoreEquivalent->verysat_from && $totals[$user->id . ','. $index[1]] <= $scoreEquivalent->verysat_to)
                                        Very Satisfactory
                                    @elseif ($totals[$user->id . ','. $index[1]] >= $scoreEquivalent->sat_from && $totals[$user->id . ','. $index[1]] <= $scoreEquivalent->sat_to)
                                        Satisfactory
                                    @elseif ($totals[$user->id . ','. $index[1]] >= $scoreEquivalent->unsat_from && $totals[$user->id . ','. $index[1]] <= $scoreEquivalent->unsat_to)
                                        Unsatisfactory
                                    @elseif ($totals[$user->id . ','. $index[1]] >= $scoreEquivalent->poor_from && $totals[$user->id . ','. $index[1]] <= $scoreEquivalent->poor_to)
                                        Poor
                                    @endif
                                </td>
                            </tr>
                            @break
                        @endif
                    @endforeach
                @endforeach
            </tfoot>
        @endif
    </table>
</body>

</html>
