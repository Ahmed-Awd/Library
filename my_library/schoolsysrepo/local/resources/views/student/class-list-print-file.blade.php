<!DOCTYPE html>
<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{$title}}</title>

    <link rel="stylesheet" href="{{CSS}}Report/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="{{CSS}}font-awesome.css"/>
    <link rel="stylesheet" href="{{CSS}}Report/bootstrap.css">
    <link rel="stylesheet" href="{{CSS}}Report/neon-core.css">
    <link rel="stylesheet" href="{{CSS}}Report/neon-theme.css">
    @if(Session::has('lang_dir') && Session::get('lang_dir') == 1 )
    <link rel="stylesheet" href="{{CSS}}Report/neon-rtl.css">
        @endif
    <style>
        @media print {
            .head th {
                background-color: #91919a !important;
                color:white !important;
                -webkit-print-color-adjust: exact;
            }}
    </style>
</head>

<body class="page-body">

<div class="page-container">

    <div class="main-content">

        <br class="hidden-print" />

        <div class="invoice">

            <div class="row">

                <div class="col-sm-4 invoice-right" style="text-align: right">
                    <h3>{{getSetting('site_title','site_settings')}}</h3>
                    <h4>{{getPhrase('address')}} : {{getSetting('site_address','site_settings')}}</h4>
                    <h4>{{getPhrase('phone')}} : {{getSetting('site_Phone','site_settings')}}</h4>
                </div>

                <div class="col-sm-4 invoice-left pull-left" style="text-align: left">
                    <a href="#">
                        <img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" width="195" style="margin-left: 80px;" alt="" />
                    </a>
                </div>

                <div class="col-sm-4 pull-right" style="text-align: left">
                    <h4>{{getPhrase('date')}} : {{\Carbon\Carbon::now()->format('d-m-Y')}}</h4>
                    <h4>{{getPhrase('academic_year')}} : {{$academic_year_title}}</h4>
                    <h4>{{$year_title}}</h4>
                    <h4>{{getPhrase('course')}} : {{$course_title}}</h4>
                </div>

            </div>


            <hr class="margin" />


            <div class="margin"></div>

            <table class="table table-bordered">
                <thead>
                <tr class="head">
                    <th style="border:1px solid #000;">{{getPhrase('sn')}}</th>
                    <th style="border:1px solid #000;" >{{getPhrase('name')}}</th>
                    <th style="border:1px solid #000;">{{getPhrase('roll_no')}}</th>
                    <th style="border:1px solid #000;">{{getPhrase('course')}}</th>
                    @for($i=1; $i<=$extracols;$i++)
                        <th style="border:1px solid #000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    @endfor
                </tr>
                </thead>

                <tbody>
                <?php $sno =1;?>
                @foreach($records as $record)
                    <tr>
                        <td style="border:1px solid #000;">{{$sno++}}</td>
                        <td style="border:1px solid #000;">{{$record['name']}}</td>
                        <td style="border:1px solid #000;">{{$record['roll_no']}}</td>
                        <td style="border:1px solid #000;">{{$record['course_title']}}</td>
                        @for($i=1; $i<=$extracols;$i++)
                            <th style="border:1px solid #000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        @endfor

                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="margin"></div>

            <div class="row">

                <div class="col-sm-12">

                    <div class="invoice-right">
                        <br />

                        <a href="javascript:window.print();" class="btn btn-primary btn-icon icon-left hidden-print">
{{getphrase('print_and_export')}}
                            <i class="fa fa-print"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>
        <!-- Footer -->

    </div>

</div>


</body>
</html>
