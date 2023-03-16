<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IPCR - Ranking</title>
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
                <th>Name</th>
                <th>Total Score</th>
                <th>Score Equivalent</th>
            </tr>
        </tbody>
        @foreach ($users as $user)
            @php
                $faculty = false;
                $staff = false;
            @endphp
            @if(!str_contains(Route::current()->getName(), 'staff') && !str_contains(Route::current()->getName(), 'faculty'))
                @foreach ($user->account_types as $account_type)
                    @if (str_contains(strtolower($account_type), 'faculty'))
                        @php
                            $faculty = true;
                        @endphp
                    @endif
                    @if (str_contains(strtolower($account_type), 'staff'))
                        @php
                            $staff = true;
                        @endphp
                    @endif
                @endforeach
            @elseif (str_contains(Route::current()->getName(), 'staff'))
                @foreach ($user->account_types as $account_type)
                    @if (str_contains(strtolower($account_type), 'staff'))
                        @php
                            $staff = true;
                        @endphp
                    @endif
                @endforeach
            @elseif (str_contains(Route::current()->getName(), 'faculty'))
                @foreach ($user->account_types as $account_type)
                    @if (str_contains(strtolower($account_type), 'faculty'))
                        @php
                            $faculty = true;
                        @endphp
                    @endif
                @endforeach
            @endif
            @if ($faculty)
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
                        @foreach ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $durationF->id)->get() as $sub_funct)
                            @php
                                $total = 0;
                                $numberSubF = 0;
                            @endphp
                            @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                @php $percent = $sub_percentage->value @endphp
                            @endif
                            @foreach ($user->outputs()->where('sub_funct_id', $sub_funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $durationF->id)->get() as $output)
                                @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $durationF->id)->get() as $suboutput)
                                    @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $durationF->id)->get() as $target)
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
                                    @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $durationF->id)->get() as $target)
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
                                        $totalCF += (($total/$numberSubF)*($percent/100)*($percentage->core/100));
                                    @endphp
                                    @break
                                @case('Strategic Function')
                                    @php
                                        if ($numberSubF == 0) {
                                            $totalSTF += 0;
                                            break;
                                        }
                                        $totalSTF += (($total/$numberSubF)*($percent/100))*($percentage->strategic/100);
                                    @endphp
                                    @break
                                @case('Support Function')
                                    @php
                                        if ($numberSubF == 0) {
                                            $totalSF += 0;
                                            break;
                                        }
                                        $totalSF += (($total/$numberSubF)*($percent/100))*($percentage->support/100);
                                    @endphp
                                    @break
                            @endswitch
                        @endforeach
                    @endif
                    @foreach ($user->outputs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $durationF->id)->get() as $output)
                        @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $durationF->id)->get() as $suboutput)
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
                        @empty
                            @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $durationF->id)->get() as $target)
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
                        @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $durationF->id)->get() as $sub_funct)
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
                        @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $durationF->id)->get() as $sub_funct)
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
                        @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $durationF->id)->get() as $sub_funct)
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
                    $totals[$user->id . ','. 'faculty'] = round($total1+$total2+$total3, 2);
                @endphp
            @endif
            
            @if ($staff)
                @if ($percentage = $user->percentages()->where('type', 'ipcr')->where('duration_id', $durationS->id)->where('user_type', 'staff')->first())
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
                            @foreach ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('duration_id', $durationS->id)->get() as $sub_funct)
                                @php
                                    $total = 0;
                                    $numberSubF = 0;
                                @endphp
                                @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                    @php $percent = $sub_percentage->value @endphp
                                @endif
                                @foreach ($user->outputs()->where('sub_funct_id', $sub_funct->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('duration_id', $durationS->id)->get() as $output)
                                    @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $durationS->id)->get() as $suboutput)
                                        @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $durationS->id)->get() as $target)
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
                                        @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $durationS->id)->get() as $target)
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
                                            $totalCF += (($total/$numberSubF)*($percent/100)*($percentage->core/100));
                                        @endphp
                                        @break
                                    @case('Strategic Function')
                                        @php
                                            if ($numberSubF == 0) {
                                                $totalSTF += 0;
                                                break;
                                            }
                                            $totalSTF += (($total/$numberSubF)*($percent/100))*($percentage->strategic/100);
                                        @endphp
                                        @break
                                    @case('Support Function')
                                        @php
                                            if ($numberSubF == 0) {
                                                $totalSF += 0;
                                                break;
                                            }
                                            $totalSF += (($total/$numberSubF)*($percent/100))*($percentage->support/100);
                                        @endphp
                                        @break
                                @endswitch
                            @endforeach
                        @endif
                        @foreach ($user->outputs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('duration_id', $durationS->id)->get() as $output)
                            @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $durationS->id)->get() as $suboutput)
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
                            @empty
                                @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $durationS->id)->get() as $target)
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
                            @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('duration_id', $durationS->id)->get() as $sub_funct)
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
                            @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('duration_id', $durationS->id)->get() as $sub_funct)
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
                            @forelse ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('duration_id', $durationS->id)->get() as $sub_funct)
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
                        $totals[$user->id . ','. 'staff'] = round($total1+$total2+$total3, 2);
                    @endphp
                @endif
            @endif
        @endforeach
        @if (isset($totals))
            <tfoot>
                @foreach ($offices as $office)
                    @php
                        $totalScore = 0;
                        $number = 0;
                        $first = true;
                    @endphp
                    @foreach ($office->users()->orderBy('name', 'ASC')->get() as $user)
                        @if (in_array($user->id, $users->pluck('id')->toArray()))
                            @foreach ($totals as $id => $total)
                                @php
                                    $index = explode( ',', $id );
                                @endphp
                                @if ($index[0] == $user->id)
                                    @if ($total != 0)
                                        @if ($first)
                                            <tr>
                                                <th colspan="3">{{ $office->office_name }}</th>
                                            </tr>
                                            @php
                                                $first = false;
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $totals[$user->id . ','. $index[1]] }}</td>
                                            @php
                                                $totalScore += $totals[$user->id . ','. $index[1]];
                                                $number++;
                                            @endphp
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
                                    @endif
                                    @break
                                @endif
                            @endforeach
                        @endif
                        @if ($totalScore != 0 && $loop->last)
                            <tr>
                                <th class="text-end">{{ $office->office_name }} Total:</th>
                                <th>{{ ($number != 0) ? $totalScore/$number  : 0; }}</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                        @endif
                    @endforeach
                @endforeach

                {{-- @foreach ($users->sortBy('name') as $user)
                    @foreach ($totals as $id => $total)
                        @php
                            $index = explode( ',', $id );
                        @endphp
                        @if ($index[0] == $user->id)
                            @if (true)
                                <tr>
                                    <td>{{ ++$number }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @foreach ($user->offices as $office)
                                            @if ($loop->last)
                                                {{ $office->office_abbr }}
                                                @break
                                            @endif
                                            {{ $office->office_abbr }} <br/> 
                                        @endforeach
                                    </td>
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
                            @endif
                            @break
                        @endif
                    @endforeach
                @endforeach --}}
            </tfoot>
        @endif
    </table>
</body>

</html>
