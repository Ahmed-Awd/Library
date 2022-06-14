@extends($layout)
@section('header_scripts')

@stop
@section('content')
<div id="page-wrapper">
     <div class="container-fluid">
       <!-- Page Heading -->
       <div class="row">
         <div class="col-lg-12">
           <ol class="breadcrumb">
             <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i> </a> </li>
                            @if(checkRole(getUserGrade(2)))
             <li><a href="{{URL_USERS_DASHBOARD}}">{{getPhrase('users_dashboard')}}</a></li>
                             @endif
             @if(checkRole(getUserGrade(2)))
             <li><a href="{{URL_USERS."student"}}">{{ getPhrase('student_users') }}</a> </li>
             @endif

             @if(checkRole(getUserGrade(7)))
             <li><a href="{{URL_PARENT_CHILDREN}}">{{ getPhrase('children') }}</a> </li>
             @endif

             <li>{{ $title }} </li>
           </ol>
         </div>

       </div>

                    <div class="panel panel-custom">
         <div class="panel-heading">
         <h1>{{ getPhrase('details_of').' '.$record->name }}</h1>
        </div>
         <div class="panel-body">
           <div class="profile-details text-center">
             <div class="profile-img"><img src="{{ getProfilePath($record->image,'profile')}}" alt=""></div>
             <div class="aouther-school">
               <h2>{{ $record->name}}</h2>
               <p><span>{{$record->email}}</span></p>
               <p><span>{{get_branch_name(Auth::user()->branch_id)}}</span></p>
               <p><span>{{get_category_name(Auth::user()->category_id)}}</span></p>
               <p><a href="{{ URL_USERS_SWITCH.$record->slug}}">
                 <sapn><i class="fa fa-sign-out" aria-hidden="true"></i> {{ getPhrase('switch_to_student') }}</sapn>
               </a></p>
             </div>

           </div>
           <hr>
           <h3 class="profile-details-title">{{ getPhrase('student_information')}}</h3>
           <div class="table-responsive vertical-scroll">
             <table class="table">
               <thead>
               <th>{{getPhrase('current_academic_year')}}</th>
               <th>{{getPhrase('current_semester')}}</th>
               <th>{{getPhrase('current_grade')}}</th>
               <th>{{getPhrase('current_class')}}</th>
               </thead>
               <tbody>
               <tr>
                 <td>{{$student_info['current_academic_year']}}</td>
                 <td>{{getPhrase($student_info['current_semester'])}}</td>
                 <td>{{$student_info['current_grade']}}</td>
                 <td>{{$student_info['current_class']}}</td>
               </tr>
               </tbody>
             </table>
           </div>
           <hr>
           <h3 class="profile-details-title">{{ getPhrase('reports')}}</h3>
       <div class="row">
       @if(checkRole(getUserGrade(1))|| checkRole(getUserGrade(2)))
       <div class="col-lg-3 col-md-6">
                       <div class="card card-green text-xs-center">
                           <div class="card-block">
                               <h4 class="card-title">
                                   <i class="fa fa-paper-plane-o"></i>
                               </h4>
                               <p style="font-size:15px" class="card-text">{{ getPhrase('EDIT_USER')}}</p>
                           </div>
                           <a class="card-footer text-muted" href="{{URL_USERS_EDIT.$record->slug}}">
                               {{ getPhrase('view_details')}}
                           </a>
       </div>
       </div>
           <div class="col-lg-3 col-md-6">
                       <div class="card card-red text-xs-center">
                           <div class="card-block">
                               <h4 class="card-title">
                                   <i class="fa fa-paper-plane-o"></i>
                               </h4>
                               <p  style="font-size:15px"  class="card-text">{{ getPhrase('EDIT_STUDENT')}}</p>
                           </div>
                           <a class="card-footer text-muted" href="{{URL_STUDENT_EDIT_PROFILE.$record->slug}}">
                               {{ getPhrase('view_details')}}
                           </a>
                       </div>
           </div>
           @endif
         @if(Module_state('exams'))
           <div class="col-lg-3 col-md-6">
           <div class="card card-blue text-xs-center">
           <div class="card-block">
             <h4 class="card-title"><i class="fa fa-history"></i></h4>
             <p class="card-text">{{ getPhrase('exam_history')}}</p>
           </div>
             <a class="card-footer text-muted" href="{{URL_STUDENT_EXAM_ATTEMPTS.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>
         </div>
         <div class="col-lg-3 col-md-6">
           <div class="card card-yellow text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-flag"></i></h4>
               <p class="card-text">{{ getPhrase('by_exam')}}</p>
             </div>
               <a class="card-footer text-muted" href="{{URL_STUDENT_ANALYSIS_BY_EXAM.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>
         </div>
         <div class="col-lg-3 col-md-6">
         <div class="card card-green text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-key"></i></h4>
               <p class="card-text">{{ getPhrase('by_subject')}}</p>
             </div>
               <a class="card-footer text-muted" href="{{URL_STUDENT_ANALYSIS_SUBJECT.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>
           </div>
         @endif

       <div class="col-lg-3 col-md-6">
           <div class="card card-red text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-credit-card"></i></h4>
               <p class="card-text">{{ getPhrase('subscriptions')}}</p>
             </div>
               <a class="card-footer text-muted" href="{{URL_PAYMENTS_LIST.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>
         </div>
         <div class="col-lg-3 col-md-6">
           <div class="card card-red text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-credit-card"></i></h4>
               <p class="card-text">{{ getPhrase('student_achievements')}}</p>
             </div>
             <a class="card-footer text-muted" href="{{URL_STUDENT_PAPERS_TABLE.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>
         </div>
           @if(Module_state('exams'))
         <div class="col-lg-3 col-md-6">
         <div class="card card-red text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-pencil"></i></h4>
               <p class="card-text">{{ getPhrase('marks')}}</p>
             </div>
               <a class="card-footer text-muted" href="{{URL_STUDENT_RESULTS.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>

         </div>
           @endif
         <div class="col-lg-3 col-md-6">

         <div class="card card-green text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-calendar"></i></h4>
               <p class="card-text">{{ getPhrase('attendance')}}</p>
             </div>
               <a class="card-footer text-muted" href="{{URL_STUDENT_ATTENDENCE_REPORT.'/'.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>

         </div>
         @if(Module_state('daily_school_schedule'))
         <div class="col-lg-3 col-md-6">
         <div class="card card-blue text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-clock-o"></i></h4>
               <p class="card-text">{{ getPhrase('timetable')}}</p>
             </div>
               <a target="_blank" class="card-footer text-muted" href="{{URL_TIMETABLE_STAFF_STUDENT_PRINT.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>



         </div>
         @endif
         @if(Module_state('library_Management'))
         <div class="col-lg-3 col-md-6">
         <div class="card card-black text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-book"></i></h4>
               <p class="card-text">{{ getPhrase('library_history')}}</p>
             </div>
               <a class="card-footer text-muted" href="{{URL_USER_LIBRARY_DETAILS.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>



         </div>
         @endif
         @if(Module_state('transfer_students'))
         <div class="col-lg-3 col-md-6">

         <div class="card card-yellow text-xs-center">
             <div class="card-block">
               <h4 class="card-title"><i class="fa fa-exchange"></i></h4>
               <p class="card-text">{{ getPhrase('transfers_list')}}</p>
             </div>
               <a class="card-footer text-muted" href="{{URL_USER_TRANSFERS_DETAILS.$record->slug}}">{{ getPhrase('view_details')}}</a>
           </div>

         </div>

           <div class="col-lg-3 col-md-6">
                       <div class="card card-green text-xs-center">
                           <div class="card-block">
                               <h4 class="card-title">
                                   <i class="fa fa-paper-plane-o"></i>
                               </h4>
                               <p class="card-text">{{ getPhrase('lesson_plans')}}</p>
                           </div>
                           <a class="card-footer text-muted" href="{{URL_STUDENT_LEASON_PLAN.$record->slug}}">
                               {{ getPhrase('view_all')}}
                           </a>
                       </div>
                   </div>

           @endif


           </div>

           </div>


         </div>
       </div>
       </div>
     <!-- /.container-fluid -->
</div>
@endsection


@section('footer_scripts')
 @if(Module_state('exams'))
@include('common.chart', array($chart_data,'ids' =>$ids));
 @endif
@stop
