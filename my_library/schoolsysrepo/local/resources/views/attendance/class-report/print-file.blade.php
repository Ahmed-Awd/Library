<html lang="en" dir="rtl">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{getPhrase('attendance_report')}}</title>

    <link rel="stylesheet" href="{{PREFIX}}css/Report/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="{{PREFIX}}css/font-awesome.css"/>
    <link rel="stylesheet" href="{{PREFIX}}css/Report/bootstrap.css">
    <link rel="stylesheet" href="{{PREFIX}}css/Report/neon-core.css">
    <link rel="stylesheet" href="{{PREFIX}}css/Report/neon-theme.css">
    <link rel="stylesheet" href="{{PREFIX}}css/Report/neon-rtl.css">
    <style>
        @media  print {
            .head th {
                background-color: #91919a !important;
                color:white !important;
                -webkit-print-color-adjust: exact;
            }}
    </style>
</head>

@section('header_scripts')


@endsection
<div class="container" id="main_frame">
    <div class="row">
        <center>
            <img style="width: 100%;" src="{{IMAGE_PATH_SETTINGS}}{{\App\Settings::get_att_logo()}}">
        </center>

    </div>
    <div class="row" style="font-size: 15px;">
        <div style="float: right;">
            <span>{{$print_year}} - {{getPhrase($print_term)}} - {{$print_course}} - {{$print_class}}</span>
        </div>
        <div style="float: left;">
            <span>{{\Carbon\Carbon::now()->format('d-m-Y')}}</span>
        </div>
    </div>
    <div class="row">
        <center>
            <h3>{{getPhrase('attendance_report')}}</h3>
        </center>
    </div>
    <div class="row">
        <center>
            <table class="table  table-bordered table-dark" style="font-size: 20px;
    border-spacing: 0" border="1">
            <thead>
            <th style="border:1px solid #000;">{{getPhrase('sn')}}</th>
            <th style="border:1px solid #000;" >{{getPhrase('name')}}</th>
            <th style="border:1px solid #000;">{{getPhrase('roll_no')}}</th>
            <th style="border:1px solid #000;">{{getPhrase('total_class')}}</th>
            <th style="border:1px solid #000;">{{getPhrase('present')}}</th>
            <th style="border:1px solid #000;">{{getPhrase('absent')}}</th>
            <th style="border:1px solid #000;">{{getPhrase('leave')}}</th>
            <th style="border:1px solid #000;">%</th>

            </thead>
            <tbody>
            @foreach($summary_attendance as $report)

            <tr>

            <td style="border:1px solid #000;">{{$report['sno']}}</td>
            <td style="border:1px solid #000;">{{$report['name']}}</td>
            <td style="border:1px solid #000;">{{$report['roll_no']}}</td>
            <td style="border:1px solid #000;">{{$report['total_classes']}}</td>
            <td style="border:1px solid #000;">{{$report['present']}}</td>
            <td style="border:1px solid #000;">{{$report['absent']}}</td>
            <td style="border:1px solid #000;">{{$report['leave']}}</td>
            <td style="border:1px solid #000;">{{$report['percentage']}}%</td>


            </tr>
            @endforeach
            </tbody>
            </table>
        </center>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="invoice-right">
                <br>
                <a href="javascript:window.print();" class="btn btn-primary btn-icon icon-left hidden-print">
                    {{getPhrase('print')}}
                    <i class="fa fa-print"></i>
                </a>
            </div>

        </div>

    </div>
</div>
</html>

<script type="text/javascript">
    var replaceDigits = function() {
        var map =
            [
                "&\#1632;","&\#1633;","&\#1634;","&\#1635;","&\#1636;",
                "&\#1637;","&\#1638;","&\#1639;","&\#1640;","&\#1641;"
            ];
        document.body.innerHTML = document.body.innerHTML.replace(/\d(?=[^<>]*(<|$))/g, function($0) { return map[$0]});
    }
    dir = document.getElementsByTagName("html")[0].getAttribute("dir");
    if(dir == 'rtl'){
        window.onload = replaceDigits;
    }

</script>

{{--<h1><b>{{$title}}</b></h1><br/>--}}
{{--<div class="row vertical-scroll">--}}

    {{--<table style="border-collapse: collapse;">--}}
    {{--<thead>--}}
        {{--<th style="border:1px solid #000;">{{getPhrase('sn')}}</th>--}}
        {{--<th style="border:1px solid #000;" >{{getPhrase('name')}}</th>--}}
        {{--<th style="border:1px solid #000;">{{getPhrase('roll_no')}}</th>--}}
        {{--<th style="border:1px solid #000;">{{getPhrase('total_class')}}</th>--}}
        {{--<th style="border:1px solid #000;">{{getPhrase('present')}}</th>--}}
        {{--<th style="border:1px solid #000;">{{getPhrase('absent')}}</th>--}}
        {{--<th style="border:1px solid #000;">{{getPhrase('leave')}}</th>--}}
        {{--<th style="border:1px solid #000;">%</th>--}}

    {{--</thead>--}}
    {{--<tbody>--}}
     {{--@foreach($summary_attendance as $report)--}}

    {{--<tr>--}}

        {{--<td style="border:1px solid #000;">{{$report['sno']}}</td>--}}
        {{--<td style="border:1px solid #000;">{{$report['name']}}</td>--}}
        {{--<td style="border:1px solid #000;">{{$report['roll_no']}}</td>--}}
        {{--<td style="border:1px solid #000;">{{$report['total_classes']}}</td>--}}
        {{--<td style="border:1px solid #000;">{{$report['present']}}</td>--}}
        {{--<td style="border:1px solid #000;">{{$report['absent']}}</td>--}}
        {{--<td style="border:1px solid #000;">{{$report['leave']}}</td>--}}
        {{--<td style="border:1px solid #000;">{{$report['percentage']}}%</td>--}}


    {{--</tr>--}}
    {{--@endforeach--}}
    {{--</tbody>--}}
    {{--</table>--}}
{{--</div>--}}
