<html lang="en" dir="rtl">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{$title}}</title>

    <link rel="stylesheet"   href="{{PREFIX}}css/Report/font-icons/entypo/css/entypo.css"  type="text/css" media="all" />
    <link rel="stylesheet"   href="{{PREFIX}}css/font-awesome.css"  type="text/css" media="all"/>
    <link rel="stylesheet"   href="{{PREFIX}}css/Report/bootstrap.css" type="text/css" media="all">
    <link rel="stylesheet"   href="{{PREFIX}}css/Report/neon-core.css" type="text/css" media="all">
    <link rel="stylesheet"   href="{{PREFIX}}css/Report/neon-theme.css" type="text/css" media="all">
    <link rel="stylesheet"   href="{{PREFIX}}css/Report/neon-rtl.css" type="text/css" media="all">
    <style>
        @media  print {
            h3{
                background-color: #91919a;
                color:red;
                -webkit-print-color-adjust:exact;
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
        {{--<div style="float: right;">--}}
            {{--<span>{{$print_year}} - {{getPhrase($print_term)}} - {{$print_course}} - {{$print_class}} - {{$print_quiz}}</span>--}}
        {{--</div>--}}
        <div style="float: left;">
            <span>{{\Carbon\Carbon::now()->format('d-m-Y')}}</span>
        </div>
    </div>
    <div class="row">
            <table class="table  table-bordered table-dark" style="font-size: 17px;width: 50%;word-spacing: 2px;
    border-spacing: 0;float: right;    margin-top: 5%;" border="1">
                <thead>
                <th style="border:1px solid #000;">{{getPhrase('name')}}</th>
                <th style="border:1px solid #000;">{{getPhrase('result')}}</th>
                </thead>
                @foreach($results as $result)
                  @if(isset($result->name))
                    <tr>
                        <td style="border:1px solid #000;">{{$result->name}}</td>

                        <td style="border:1px solid #000;"><p>{{$result->marks_obtained}}</p></td>

                    </tr>
                    @else
                      <tr>
                          <td style="border:1px solid #000;">{{$result}}</td>

                          <td style="border:1px solid #000;"><p>x</p></td>

                      </tr>

                    @endif
                @endforeach
            </table>

        <table class="table  table-bordered table-dark" style="margin-top:5%;font-size: 17px;width: 44%;word-spacing: 2px;
    border-spacing: 0;float: left;" border="1">
                <tr>
                    <th style="background-color: grey;color: white">{{getPhrase('teacher_name')}}</th>
                    <th>{{$teacher_name}}</th>
                </tr>
            <tr>
                <th style="background-color: grey;color: white">{{getPhrase('academic_year')}}</th>
                <th>{{$print_year}}</th>
            </tr>
            <tr>
                <th style="background-color: grey;color: white">{{getPhrase('Semester')}}</th>
                <th>{{getPhrase($print_term)}}</th>
            </tr>
            <tr>
                <th style="background-color: grey;color: white">{{getPhrase('branch')}}</th>
                <th>{{$print_course}}</th>
            </tr>
            <tr>
                <th style="background-color: grey;color: white">{{getPhrase('class')}}</th>
                <th>{{$print_class}}</th>
            </tr>
            <tr>
                <th style="background-color: grey;color: white">{{getPhrase('subject')}}</th>
                <th>{{$print_subject}}</th>
            </tr>
            <tr>
                <th style="background-color: grey;color: white">{{getPhrase('exam')}}</th>
                <th>{{$print_quiz}}</th>
            </tr>
            <tr>
                <th style="background-color: grey;color: white">{{getPhrase('total_marks')}}</th>
                <th>{{$max_mark}}</th>
            </tr>
        </table>

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
