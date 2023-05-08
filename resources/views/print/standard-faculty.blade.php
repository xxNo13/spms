<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Standard - {{ Auth::user()->name }}</title>
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
        table.main-table  tr.page-break {
            page-break-after: avoid !important;
            break-after: avoid-page !important;
            margin: 4px 0 4px 0
        }
        table.main-table  tr  td, table.main-table tr th {
            page-break-inside: avoid !important;
            break-inside: avoid-page !important;
            margin: 4px 0 4px 0
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
    
    <h1 class="text-center" style="font-size: 12px;">{{ date('Y', strtotime($duration->start_date)) }} PERFORMANCE STANDARD ( SEMESTRAL )</h1>

    <table class="main-table bordered">
        <tbody>
            <tr>
                <th colspan="2">Output</th>
                <th>Success Indicator</th>
                <th>Rating</th>
                <th>Quality Standard</th>
                <th>Rating</th>
                <th>Effeciency Standard</th>
                <th>Rating</th>
                <th>Timeliness Standard</th>
            </tr>
            @php
                $number = 0;
            @endphp
            @foreach ($functs as $funct)
                <tr>
                    <td class="text-start" colspan="9">
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
                    </td>
                </tr>
                @foreach ($user->sub_functs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $sub_funct)
                    <tr>
                        <td colspan="2">
                            {{ $sub_funct->sub_funct }}
                            @if ($sub_percentage = $user->sub_percentages()->where('sub_funct_id', $sub_funct->id)->first())
                                {{ $percent = $sub_percentage->value }}%
                            @endif
                        </td>
                        <td colspan="7"></td>
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
                                <td colspan="7"></td>
                            </tr>
                            <tr class="page-break">
                                <td colspan="2" rowspan="{{ count($suboutput->targets) * 5}}">
                                {{ $suboutput->suboutput }}
                                </td>

                                @php
                                    $first = true;
                                @endphp
                                @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                    @if ($first)
                                        @forelse ($target->standards as $standard)
                                            @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->qua_5 ? $standard->qua_5 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->eff_5 ? $standard->eff_5 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->time_5 ? $standard->time_5 : 'NR' }}</td>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->qua_4 ? $standard->qua_4 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->eff_4 ? $standard->eff_4 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->time_4 ? $standard->time_4 : 'NR' }}</td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->qua_3 ? $standard->qua_3 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->eff_3 ? $standard->eff_3 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->time_3 ? $standard->time_3 : 'NR' }}</td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->qua_2 ? $standard->qua_2 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->eff_2 ? $standard->eff_2 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->time_2 ? $standard->time_2 : 'NR' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->qua_1 ? $standard->qua_1 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->eff_1 ? $standard->eff_1 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->time_1 ? $standard->time_1 : 'NR' }}</td>
                                                </tr>
                                                @break
                                            @elseif ($loop->last)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @empty
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endforelse
                                        @php
                                            $first = false;
                                        @endphp
                                    @else
                                        <tr class="page-break">
                                            @forelse ($target->standards as $standard)
                                                @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                    <td rowspan="5">{{ $target->target }}</td>
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '5' : '' }}</td>
                                                    <td>{{ $standard->qua_5 ? $standard->qua_5 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '5' : '' }}</td>
                                                    <td>{{ $standard->eff_5 ? $standard->eff_5 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '5' : '' }}</td>
                                                    <td>{{ $standard->time_5 ? $standard->time_5 : 'NR' }}</td>
                                                    <tr class="page-break">
                                                        <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '4' : '' }}</td>
                                                        <td>{{ $standard->qua_4 ? $standard->qua_4 : 'NR' }}</td>
                                                        <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '4' : '' }}</td>
                                                        <td>{{ $standard->eff_4 ? $standard->eff_4 : 'NR' }}</td>
                                                        <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '4' : '' }}</td>
                                                        <td>{{ $standard->time_4 ? $standard->time_4 : 'NR' }}</td>
                                                    </tr>
                                                    <tr class="page-break">
                                                        <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '3' : '' }}</td>
                                                        <td>{{ $standard->qua_3 ? $standard->qua_3 : 'NR' }}</td>
                                                        <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '3' : '' }}</td>
                                                        <td>{{ $standard->eff_3 ? $standard->eff_3 : 'NR' }}</td>
                                                        <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '3' : '' }}</td>
                                                        <td>{{ $standard->time_3 ? $standard->time_3 : 'NR' }}</td>
                                                    </tr>
                                                    <tr class="page-break">
                                                        <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '2' : '' }}</td>
                                                        <td>{{ $standard->qua_2 ? $standard->qua_2 : 'NR' }}</td>
                                                        <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '2' : '' }}</td>
                                                        <td>{{ $standard->eff_2 ? $standard->eff_2 : 'NR' }}</td>
                                                        <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '2' : '' }}</td>
                                                        <td>{{ $standard->time_2 ? $standard->time_2 : 'NR' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '1' : '' }}</td>
                                                        <td>{{ $standard->qua_1 ? $standard->qua_1 : 'NR' }}</td>
                                                        <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '1' : '' }}</td>
                                                        <td>{{ $standard->eff_1 ? $standard->eff_1 : 'NR' }}</td>
                                                        <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '1' : '' }}</td>
                                                        <td>{{ $standard->time_1 ? $standard->time_1 : 'NR' }}</td>
                                                    </tr>
                                                    @break
                                                @elseif ($loop->last)
                                                    <td rowspan="5">{{ $target->target }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <tr class="page-break">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr class="page-break">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr class="page-break">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforelse
                                        </tr>
                                    @endif
                                @endforeach
                            </tr>
                        @empty
                            <tr class="page-break">
                                <td rowspan="{{ count($output->targets)*5 }}">
                                    {{ $output->code }} {{ ++$number }}
                                </td>
                                <td rowspan="{{ count($output->targets)*5 }}">
                                    {{ $output->output }}
                                </td>
                                @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                    @forelse ($target->standards as $standard)
                                        @if ($standard->user_id == $user->id || $standard->user_id == null)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->qua_5 ? $standard->qua_5 : 'NR' }}</td>
                                            <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->eff_5 ? $standard->eff_5 : 'NR' }}</td>
                                            <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->time_5 ? $standard->time_5 : 'NR' }}</td>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->qua_4 ? $standard->qua_4 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->eff_4 ? $standard->eff_4 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->time_4 ? $standard->time_4 : 'NR' }}</td>
                                            </tr>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->qua_3 ? $standard->qua_3 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->eff_3 ? $standard->eff_3 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->time_3 ? $standard->time_3 : 'NR' }}</td>
                                            </tr>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->qua_2 ? $standard->qua_2 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->eff_2 ? $standard->eff_2 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->time_2 ? $standard->time_2 : 'NR' }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->qua_1 ? $standard->qua_1 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->eff_1 ? $standard->eff_1 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->time_1 ? $standard->time_1 : 'NR' }}</td>
                                            </tr>
                                            @break
                                        @elseif ($loop->last)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @empty
                                        <td rowspan="5">{{ $target->target }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                @endforeach
                            </tr>
                        @endforelse
                    @endforeach
                @endforeach
                @foreach ($user->outputs()->where('funct_id', $funct->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('duration_id', $duration->id)->get() as $output)
                    @forelse ($user->suboutputs()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $suboutput)
                        <tr>
                            <td>
                                {{ $output->code }} {{ ++$number }}
                            </td>
                            <td>
                                {{ $output->output }}
                            </td>
                            <td colspan="7"></td>
                        </tr>
                        <tr class="page-break">
                            <td colspan="2" rowspan="{{ count($suboutput->targets) * 5 }}">
                            {{ $suboutput->suboutput }}
                            </td>

                            @php
                                $first = true;
                            @endphp
                            @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                @if ($first)
                                    @forelse ($target->standards as $standard)
                                        @if ($standard->user_id == $user->id || $standard->user_id == null)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->qua_5 ? $standard->qua_5 : 'NR' }}</td>
                                            <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->eff_5 ? $standard->eff_5 : 'NR' }}</td>
                                            <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->time_5 ? $standard->time_5 : 'NR' }}</td>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->qua_4 ? $standard->qua_4 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->eff_4 ? $standard->eff_4 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->time_4 ? $standard->time_4 : 'NR' }}</td>
                                            </tr>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->qua_3 ? $standard->qua_3 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->eff_3 ? $standard->eff_3 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->time_3 ? $standard->time_3 : 'NR' }}</td>
                                            </tr>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->qua_2 ? $standard->qua_2 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->eff_2 ? $standard->eff_2 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->time_2 ? $standard->time_2 : 'NR' }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->qua_1 ? $standard->qua_1 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->eff_1 ? $standard->eff_1 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->time_1 ? $standard->time_1 : 'NR' }}</td>
                                            </tr>
                                            @break
                                        @elseif ($loop->last)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @empty
                                        <td rowspan="5">{{ $target->target }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                    @php
                                        $first = false;
                                    @endphp
                                @else
                                    <tr class="page-break">
                                        @forelse ($target->standards as $standard)
                                            @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->qua_5 ? $standard->qua_5 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->eff_5 ? $standard->eff_5 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->time_5 ? $standard->time_5 : 'NR' }}</td>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->qua_4 ? $standard->qua_4 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->eff_4 ? $standard->eff_4 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->time_4 ? $standard->time_4 : 'NR' }}</td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->qua_3 ? $standard->qua_3 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->eff_3 ? $standard->eff_3 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->time_3 ? $standard->time_3 : 'NR' }}</td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->qua_2 ? $standard->qua_2 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->eff_2 ? $standard->eff_2 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->time_2 ? $standard->time_2 : 'NR' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->qua_1 ? $standard->qua_1 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->eff_1 ? $standard->eff_1 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->time_1 ? $standard->time_1 : 'NR' }}</td>
                                                </tr>
                                                @break
                                            @elseif ($loop->last)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @empty
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endforelse
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr class="page-break">
                            <td rowspan="{{ count($output->targets)*5 }}">
                                {{ $output->code }} {{ ++$number }}
                            </td>
                            <td rowspan="{{ count($output->targets)*5 }}">
                                {{ $output->output }}
                            </td>

                            @php
                                $first = true;
                            @endphp
                            @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                @if ($first)
                                    @forelse ($target->standards as $standard)
                                        @if ($standard->user_id == $user->id || $standard->user_id == null)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->qua_5 ? $standard->qua_5 : 'NR' }}</td>
                                            <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->eff_5 ? $standard->eff_5 : 'NR' }}</td>
                                            <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '5' : '' }}</td>
                                            <td>{{ $standard->time_5 ? $standard->time_5 : 'NR' }}</td>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->qua_4 ? $standard->qua_4 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->eff_4 ? $standard->eff_4 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '4' : '' }}</td>
                                                <td>{{ $standard->time_4 ? $standard->time_4 : 'NR' }}</td>
                                            </tr>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->qua_3 ? $standard->qua_3 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->eff_3 ? $standard->eff_3 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '3' : '' }}</td>
                                                <td>{{ $standard->time_3 ? $standard->time_3 : 'NR' }}</td>
                                            </tr>
                                            <tr class="page-break">
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->qua_2 ? $standard->qua_2 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->eff_2 ? $standard->eff_2 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '2' : '' }}</td>
                                                <td>{{ $standard->time_2 ? $standard->time_2 : 'NR' }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->qua_1 ? $standard->qua_1 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->eff_1 ? $standard->eff_1 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '1' : '' }}</td>
                                                <td>{{ $standard->time_1 ? $standard->time_1 : 'NR' }}</td>
                                            </tr>
                                            @break
                                        @elseif ($loop->last)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @empty
                                        <td rowspan="5">{{ $target->target }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="page-break">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                    @php
                                        $first = false;
                                    @endphp
                                @else
                                    <tr class="page-break">
                                        @forelse ($target->standards as $standard)
                                            @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->qua_5 ? $standard->qua_5 : 'NR' }}</td>
                                                <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->eff_5 ? $standard->eff_5 : 'NR' }}</td>
                                                <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '5' : '' }}</td>
                                                <td>{{ $standard->time_5 ? $standard->time_5 : 'NR' }}</td>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->qua_4 ? $standard->qua_4 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->eff_4 ? $standard->eff_4 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '4' : '' }}</td>
                                                    <td>{{ $standard->time_4 ? $standard->time_4 : 'NR' }}</td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->qua_3 ? $standard->qua_3 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->eff_3 ? $standard->eff_3 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '3' : '' }}</td>
                                                    <td>{{ $standard->time_3 ? $standard->time_3 : 'NR' }}</td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->qua_2 ? $standard->qua_2 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->eff_2 ? $standard->eff_2 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '2' : '' }}</td>
                                                    <td>{{ $standard->time_2 ? $standard->time_2 : 'NR' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ ($standard->qua_5 || $standard->qua_4 || $standard->qua_3 || $standard->qua_2 || $standard->qua_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->qua_1 ? $standard->qua_1 : 'NR' }}</td>
                                                    <td>{{ ($standard->eff_5 || $standard->eff_4 || $standard->eff_3 || $standard->eff_2 || $standard->eff_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->eff_1 ? $standard->eff_1 : 'NR' }}</td>
                                                    <td>{{ ($standard->time_5 || $standard->time_4 || $standard->time_3 || $standard->time_2 || $standard->time_1) ? '1' : '' }}</td>
                                                    <td>{{ $standard->time_1 ? $standard->time_1 : 'NR' }}</td>
                                                </tr>
                                                @break
                                            @elseif ($loop->last)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="page-break">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @empty
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="page-break">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endforelse
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @endforelse
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-start" style="height: 100px; vertical-align: top;">
                    Comment and recommendation for Development Purposes
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>