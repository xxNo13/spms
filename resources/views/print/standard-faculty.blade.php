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
    </style>
</head>

<body>
    <div id="header">
        <img src="{{ public_path('images/logo/header.jpg') }}">
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
                <th>Effeciency Standard</th>
                <th>Rating</th>
                <th>Quality Standard</th>
                <th>Rating</th>
                <th>Timeliness Standard</th>
            </tr>
            @php
                $number = 0;
            @endphp
            @foreach ($functs as $funct)
                <tr>
                    <td class="text-start" colspan="9">{{ $funct->funct }}</td>
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
                            <tr style="page-break-inside: avoid;" >
                                <td colspan="2" rowspan="{{ count($suboutput->targets) * 5}}">
                                {{ $suboutput->suboutput }}
                                </td>

                                @php
                                    $first = true;
                                @endphp
                                @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                    @if ($first)
                                        @foreach ($target->standards as $standard)
                                            @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->eff_5 }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->qua_5 }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->time_5 }}</td>
                                                <tr>
                                                    <td>4</td>
                                                    <td>{{ $standard->eff_4 }}</td>
                                                    <td>4</td>
                                                    <td>{{ $standard->qua_4 }}</td>
                                                    <td>4</td>
                                                    <td>{{ $standard->time_4 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>{{ $standard->eff_3 }}</td>
                                                    <td>3</td>
                                                    <td>{{ $standard->qua_3 }}</td>
                                                    <td>3</td>
                                                    <td>{{ $standard->time_3 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>{{ $standard->eff_2 }}</td>
                                                    <td>2</td>
                                                    <td>{{ $standard->qua_2 }}</td>
                                                    <td>2</td>
                                                    <td>{{ $standard->time_2 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ $standard->eff_1 }}</td>
                                                    <td>1</td>
                                                    <td>{{ $standard->qua_1 }}</td>
                                                    <td>1</td>
                                                    <td>{{ $standard->time_1 }}</td>
                                                </tr>
                                                @break
                                            @endif
                                        @endforeach
                                        @php
                                            $first = false;
                                        @endphp
                                    @else
                                        <tr style="page-break-inside: avoid;" >
                                            @foreach ($target->standards as $standard)
                                                @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                    <td rowspan="5">{{ $target->target }}</td>
                                                    <td>5</td>
                                                    <td>{{ $standard->eff_5 }}</td>
                                                    <td>5</td>
                                                    <td>{{ $standard->qua_5 }}</td>
                                                    <td>5</td>
                                                    <td>{{ $standard->time_5 }}</td>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>{{ $standard->eff_4 }}</td>
                                                        <td>4</td>
                                                        <td>{{ $standard->qua_4 }}</td>
                                                        <td>4</td>
                                                        <td>{{ $standard->time_4 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>{{ $standard->eff_3 }}</td>
                                                        <td>3</td>
                                                        <td>{{ $standard->qua_3 }}</td>
                                                        <td>3</td>
                                                        <td>{{ $standard->time_3 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>{{ $standard->eff_2 }}</td>
                                                        <td>2</td>
                                                        <td>{{ $standard->qua_2 }}</td>
                                                        <td>2</td>
                                                        <td>{{ $standard->time_2 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>{{ $standard->eff_1 }}</td>
                                                        <td>1</td>
                                                        <td>{{ $standard->qua_1 }}</td>
                                                        <td>1</td>
                                                        <td>{{ $standard->time_1 }}</td>
                                                    </tr>
                                                    @break
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                            </tr>
                        @empty
                            <tr style="page-break-inside: avoid;" >
                                <td rowspan="{{ count($output->targets)*5 }}">
                                    {{ $output->code }} {{ ++$number }}
                                </td>
                                <td rowspan="{{ count($output->targets)*5 }}">
                                    {{ $output->output }}
                                </td>
                                @foreach ($user->targets()->where('output_id', $output->id)->where('duration_id', $duration->id)->get() as $target)
                                    @foreach ($target->standards as $standard)
                                        @if ($standard->user_id == $user->id || $standard->user_id == null)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->eff_5 }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->qua_5 }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->time_5 }}</td>
                                            <tr>
                                                <td>4</td>
                                                <td>{{ $standard->eff_4 }}</td>
                                                <td>4</td>
                                                <td>{{ $standard->qua_4 }}</td>
                                                <td>4</td>
                                                <td>{{ $standard->time_4 }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>{{ $standard->eff_3 }}</td>
                                                <td>3</td>
                                                <td>{{ $standard->qua_3 }}</td>
                                                <td>3</td>
                                                <td>{{ $standard->time_3 }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>{{ $standard->eff_2 }}</td>
                                                <td>2</td>
                                                <td>{{ $standard->qua_2 }}</td>
                                                <td>2</td>
                                                <td>{{ $standard->time_2 }}</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $standard->eff_1 }}</td>
                                                <td>1</td>
                                                <td>{{ $standard->qua_1 }}</td>
                                                <td>1</td>
                                                <td>{{ $standard->time_1 }}</td>
                                            </tr>
                                            @break
                                        @endif
                                    @endforeach
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
                        <tr style="page-break-inside: avoid;" >
                            <td colspan="2" rowspan="{{ count($suboutput->targets) * 5 }}">
                            {{ $suboutput->suboutput }}
                            </td>

                            @php
                                $first = true;
                            @endphp
                            @foreach ($user->targets()->where('suboutput_id', $suboutput->id)->where('duration_id', $duration->id)->get() as $target)
                                @if ($first)
                                    @foreach ($target->standards as $standard)
                                        @if ($standard->user_id == $user->id || $standard->user_id == null)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->eff_5 }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->qua_5 }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->time_5 }}</td>
                                            <tr>
                                                <td>4</td>
                                                <td>{{ $standard->eff_4 }}</td>
                                                <td>4</td>
                                                <td>{{ $standard->qua_4 }}</td>
                                                <td>4</td>
                                                <td>{{ $standard->time_4 }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>{{ $standard->eff_3 }}</td>
                                                <td>3</td>
                                                <td>{{ $standard->qua_3 }}</td>
                                                <td>3</td>
                                                <td>{{ $standard->time_3 }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>{{ $standard->eff_2 }}</td>
                                                <td>2</td>
                                                <td>{{ $standard->qua_2 }}</td>
                                                <td>2</td>
                                                <td>{{ $standard->time_2 }}</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $standard->eff_1 }}</td>
                                                <td>1</td>
                                                <td>{{ $standard->qua_1 }}</td>
                                                <td>1</td>
                                                <td>{{ $standard->time_1 }}</td>
                                            </tr>
                                            @break
                                        @endif
                                    @endforeach
                                    @php
                                        $first = false;
                                    @endphp
                                @else
                                    <tr style="page-break-inside: avoid;" >
                                        @foreach ($target->standards as $standard)
                                            @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->eff_5 }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->qua_5 }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->time_5 }}</td>
                                                <tr>
                                                    <td>4</td>
                                                    <td>{{ $standard->eff_4 }}</td>
                                                    <td>4</td>
                                                    <td>{{ $standard->qua_4 }}</td>
                                                    <td>4</td>
                                                    <td>{{ $standard->time_4 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>{{ $standard->eff_3 }}</td>
                                                    <td>3</td>
                                                    <td>{{ $standard->qua_3 }}</td>
                                                    <td>3</td>
                                                    <td>{{ $standard->time_3 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>{{ $standard->eff_2 }}</td>
                                                    <td>2</td>
                                                    <td>{{ $standard->qua_2 }}</td>
                                                    <td>2</td>
                                                    <td>{{ $standard->time_2 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ $standard->eff_1 }}</td>
                                                    <td>1</td>
                                                    <td>{{ $standard->qua_1 }}</td>
                                                    <td>1</td>
                                                    <td>{{ $standard->time_1 }}</td>
                                                </tr>
                                                @break
                                            @endif
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr style="page-break-inside: avoid;" >
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
                                    @foreach ($target->standards as $standard)
                                        @if ($standard->user_id == $user->id || $standard->user_id == null)
                                            <td rowspan="5">{{ $target->target }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->eff_5 }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->qua_5 }}</td>
                                            <td>5</td>
                                            <td>{{ $standard->time_5 }}</td>
                                            <tr>
                                                <td>4</td>
                                                <td>{{ $standard->eff_4 }}</td>
                                                <td>4</td>
                                                <td>{{ $standard->qua_4 }}</td>
                                                <td>4</td>
                                                <td>{{ $standard->time_4 }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>{{ $standard->eff_3 }}</td>
                                                <td>3</td>
                                                <td>{{ $standard->qua_3 }}</td>
                                                <td>3</td>
                                                <td>{{ $standard->time_3 }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>{{ $standard->eff_2 }}</td>
                                                <td>2</td>
                                                <td>{{ $standard->qua_2 }}</td>
                                                <td>2</td>
                                                <td>{{ $standard->time_2 }}</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $standard->eff_1 }}</td>
                                                <td>1</td>
                                                <td>{{ $standard->qua_1 }}</td>
                                                <td>1</td>
                                                <td>{{ $standard->time_1 }}</td>
                                            </tr>
                                            @break
                                        @endif
                                    @endforeach
                                    @php
                                        $first = false;
                                    @endphp
                                @else
                                    <tr style="page-break-inside: avoid;" >
                                        @foreach ($target->standards as $standard)
                                            @if ($standard->user_id == $user->id || $standard->user_id == null)
                                                <td rowspan="5">{{ $target->target }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->eff_5 }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->qua_5 }}</td>
                                                <td>5</td>
                                                <td>{{ $standard->time_5 }}</td>
                                                <tr>
                                                    <td>4</td>
                                                    <td>{{ $standard->eff_4 }}</td>
                                                    <td>4</td>
                                                    <td>{{ $standard->qua_4 }}</td>
                                                    <td>4</td>
                                                    <td>{{ $standard->time_4 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>{{ $standard->eff_3 }}</td>
                                                    <td>3</td>
                                                    <td>{{ $standard->quafaculty_3 }}</td>
                                                    <td>3</td>
                                                    <td>{{ $standard->time_3 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>{{ $standard->eff_2 }}</td>
                                                    <td>2</td>
                                                    <td>{{ $standard->qua_2 }}</td>
                                                    <td>2</td>
                                                    <td>{{ $standard->time_2 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ $standard->eff_1 }}</td>
                                                    <td>1</td>
                                                    <td>{{ $standard->qua_1 }}</td>
                                                    <td>1</td>
                                                    <td>{{ $standard->time_1 }}</td>
                                                </tr>
                                                @break
                                            @endif
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @endforelse
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>