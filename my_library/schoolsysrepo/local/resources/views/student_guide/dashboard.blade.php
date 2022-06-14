@extends($layout)
@section('header_scripts')
    {!! Charts::assets() !!}
@stop

@section('content')
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">

                            <li><i class="fa fa-home"></i> {{ $title}}</li> - {{get_branch_name(Auth::user()->branch_id)}} - {{get_category_name(Auth::user()->category_id)}}
                        </ol>
                    </div>
                </div>

                <div class="row">


                    <div class="col-md-3">
                        <div class="card card-blue text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-street-view"></i>
                                </h4>
                                <p class="card-text">{{ getPhrase('search_student') }}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{url('user/search')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-blue text-xs-center helper_step1">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-id-card" aria-hidden="true"></i>
                                </h4>

                                <p class="card-text">{{ getPhrase('id_cards')}}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{URL_CERTIFICATES_GENERATE_IDCARD}}">
                                {{ getPhrase('lets_start')}}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-red text-xs-center helper_step2">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-street-view"></i>

                                </h4>
                                <p class="card-text">{{ getPhrase('Attendance_and_departure')}}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{URL_STUDENT_ATTENDENCE.Auth::user()->slug}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-orange text-xs-center helper_step3">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-assistive-listening-systems"></i>
                                </h4>
                                <p class="card-text">{{ getPhrase('Automatic_call')}}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{url('attendanceoperations/autocall/view')}}" target="_blank">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-yellow text-xs-center helper_step4">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-microphone"></i>

                                </h4>
                                <p class="card-text">{{ getPhrase('voice_records_for_student')}}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{url('attendanceoperations/record-student')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <div class="card card-black text-xs-center helper_step6">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                </h4>

                                <p class="card-text">{{ getPhrase('Attendance_and_departure_report')}}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{ATTENDANCE_REPORT_VIEW}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-blue text-xs-center helper_step5">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-check-square-o">	</i>
                                </h4>

                                <p class="card-text">{{ getPhrase('class_attendance_report')}}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{URL_STUDENT_CLASS_ATTENDANCE}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>





                </div>
            </div>
            <!-- /#page-wrapper -->
        </div>
@stop

@section('footer_scripts')


@stop
