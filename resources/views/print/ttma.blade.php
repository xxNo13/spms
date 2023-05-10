<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tracking Tool and Moonitoring Assignment</title>
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
            font-size: 11px;
            font-family: Calibri, sans-serif;
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
        <img src="uploads/{{ $printImage->header_link }}">
    </div>
    <div id="footer">
        <img src="uploads/{{ $printImage->footer_link }}">
    </div>
    
    <h1 class="text-center" style="font-size: 12px;">{{ date('Y') }} Tracking Tool for Monitoring Assignments</h1>
    <h1 class="text-center" style="font-size: 12px;">{{ auth()->user()->name }}</h1>

    <table class="main-table bordered">
        <tbody>
            <tr>
                <th colspan="7">Performance Monitoring Form</th>
            </tr>
            <tr>
                <td><b>Tasks ID No.</b> <br /> (Document No. or Task No. of Taken from WFP)</td>
                <td><b>Subject</b> <br /> (Subject Area of the Task or the Signatory of the Document and Subject Area)</td>
                <th>Action Officer/s</th>
                <th>Output</th>
                <td><b>Date Assigned</b> <br /> (Date the Task was assigned to the drafter)</td>
                <td><b>Date Accomplished</b> <br /> (Date the output was approved by the approver)</td>
                <th>Remarks</th>
            </tr>

            @foreach ($ttmas as $ttma)
                <tr>
                    <td>{{ sprintf('%03u', $ttma->id) }}</td>
                    <td>{{ $ttma->subject }}</td>
                    <td>
                        @if (count($ttma->users) > 2)
                            @foreach ($ttma->users as $user)
                                @if ($loop->last)
                                    {{ $user->name }}
                                    @break
                                @endif
                                {{ $user->name }}, 
                            @endforeach
                        @else
                            {{ $ttma->users()->first()->name }}
                        @endif
                    </td>
                    <td>{{ $ttma->output }}</td>
                    <td>{{ date('M d, Y', strtotime($ttma->created_at)) }}</td>
                    <td>
                        @if ($ttma->remarks)
                            {{ date('M d, Y', strtotime($ttma->updated_at)) }}
                        @endif
                    </td>
                    <td>{{ $ttma->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
