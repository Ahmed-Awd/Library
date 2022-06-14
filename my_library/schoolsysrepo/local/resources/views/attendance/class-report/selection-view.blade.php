@extends($layout)

@section('header_scripts')

<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
@stop

@section('content')
<div id="page-wrapper" ng-controller="TabController">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{PREFIX}}">
                            <i class="mdi mdi-home">
                            </i>
                        </a>
                    </li>
                    <li>
                        <a href="{{URL_AttendanceOperations_DASHBOARD}}">
                            {{getPhrase('Attendance_and_departure')}}
                        </a>

                    </li>

                    <li>
                            {{$title}}
                    </li>
                </ol>
            </div>
        </div>

        {!! Form::open(array('url' => URL_PRINT_CLASS_ATTENDANCE_REPORT, 'method' => 'POST', 'name'=>'htmlform ','target'=>'_blank', 'id'=>'htmlform', 'novalidate'=>'')) !!}


        <div class="panel panel-custom">
            <div class="panel-heading">
                <h1>
                    {{getPhrase('select_details')}}

                </h1>
            </div>
            <div class="panel-body instruction">
                <div class="row">
                    <div class="col-md-6">
                        <fieldset class="form-group">
                            <label for="">{{getPhrase('academic_year')}}</label>
                            <span class="text-red">*</span>
                            <select name="academic_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
                                <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset class="form-group">
                            <label for="">{{getPhrase('Semester')}}</label>
                            <span class="text-red">*</span>
                            <select name="current_semister" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getCourses()">
                                <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <fieldset class="form-group">
                            <label for="">{{getPhrase('branch')}}</label>
                            <span class="text-red">*</span>
                            <select name="course_parent_id" class="form-control" required="required"
                                    ng-model="current_course_sc" ng-change="getClasses()">
                                <option ng-repeat="course in academic_courses_sc"
                                        value="@{{ course.id }}">@{{ course.course_title }}</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset class="form-group">
                            <label for="">{{getPhrase('class')}}</label>
                            <span class="text-red">*</span>
                            <select name="course_id" class="form-control" required="required" ng-change="doCall()"
                                    ng-model="current_sub_course">
                                <option ng-repeat="aclass in subcourses"
                                        value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <hr>

   <div ng-show="result_data.length>0" class="row">

   <div class="col-sm-4 col-sm-offset-8">
            <div class="input-group">
                    <input type="text" ng-model="search" class="form-control input-lg" placeholder="{{getPhrase('search')}}" name="search" />
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
        </div>
   </div>
   <br>
 <div class="row vertical-scroll" id="printable_data">
     <div class="row" id="right_data" style="text-align: left; display: none;margin-bottom: 10%;">
         <div class="col-sm-4 invoice-right" style="text-align: right">
             <h3>{{getSetting('site_title','site_settings')}}</h3>
             <h4>{{getPhrase('address')}} : {{getSetting('site_address','site_settings')}}</h4>
             <h4>{{getPhrase('phone')}} : {{getSetting('site_Phone','site_settings')}}</h4>
         </div>

         <div class="col-sm-4 pull-right">
             <h4>{{getPhrase('date')}} : {{\Carbon\Carbon::now()->format('d-m-Y')}}</h4>
             <h4>{{getPhrase('academic_year')}} : <span id="c_year"></span> </h4>
             <h4 id="print_course_parent"></h4>
             <h4>{{getPhrase('course')}} : <span id="print_course"></span></h4>
         </div>
     </div>
    <table ng-if="result_data.length>0" class="table table-bordered" style="border-collapse: collapse;">
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
    <tr ng-repeat="user in result_data | filter:search track by $index">
        <td style="border:1px solid #000;">@{{user.sno}}</td>
        <td style="border:1px solid #000;"><a href="{{URL_USER_DETAILS}}@{{user.slug}}">@{{user.name}}</a></td>
        <td style="border:1px solid #000;">@{{user.roll_no}}</td>
        <td style="border:1px solid #000;">@{{user.total_classes}}</td>
        <td style="border:1px solid #000;">@{{user.present}}</td>
        <td style="border:1px solid #000;">@{{user.absent}}</td>
        <td style="border:1px solid #000;">@{{user.leave}}</td>
        <td style="border:1px solid #000;">

        <div class="progress">
          <div  ng-class="{'progress-bar progress-bar-success':user.percentage>=75, 'progress-bar progress-bar-warning':user.percentage<75 && user.percentage>=50, 'progress-bar progress-bar-danger':user.percentage<50 && user.percentage>=0}" role="progressbar" aria-valuenow="@{{user.percentage}}"
          aria-valuemin="0" aria-valuemax="100" style="width:@{{user.percentage}}%">
            @{{user.percentage}}%
          </div>
        </div>

        </td>
    </tr>
    </tbody>
    </table>
</div>

<div ng-if="result_data.length==0" class="text-center" >{{getPhrase('no_data_available')}}</div>
<br>
<button ng-if="result_data.length>0"  class="btn btn-primary" type="submit">{{getPhrase('Print')}}</button>
  </div>
</div>



                </hr>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}



@stop




@section('footer_scripts')


    @include('attendance.class-report.scripts.js-scripts')

@stop
<script>
    function PrintElem(elem)
    {
        var mywindow = window.open('', 'PRINT', 'height=800,width=800');
        $("#print_course_parent").text($("#course_parent_id option:selected").text());
        $("#print_course").text($("#course_id option:selected").text());
        $("#c_year").text($("#select_academic_year option:selected").text());
        mywindow.document.write('<html dir="rtl"><head><title>' + document.title  + '</title>');
        mywindow.document.write('<link href="{{PREFIX}}css/bootstrap.min.css" rel="stylesheet">');
        mywindow.document.write('</head><body>');
        mywindow.document.write('<style>@media print { a[href]:after {content: none !important;    }      } #right_data{ display:block!important;}        </style>');
        mywindow.document.write('<center><h1>' + document.title  + '</h1></center>');
        mywindow.document.write(document.getElementById("printable_data").innerHTML);
        mywindow.document.write('<div class="row"><div class="col-sm-12"><div class="invoice-right"><br><a href="javascript:window.print();" class="btn btn-primary btn-icon icon-left hidden-print">طباعة وتصدير <i class="fa fa-print"></i> </a> </div> </div>');
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

//            mywindow.print();
//            mywindow.close();

        return true;
    }
</script>