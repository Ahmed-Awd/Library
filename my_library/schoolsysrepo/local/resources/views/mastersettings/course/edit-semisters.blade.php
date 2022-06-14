{{--<script src="{{url('/js/jquery.min.js')}}"></script>--}}
@extends('layouts.admin.adminlayout')
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="mdi mdi-home"></i></a></li>
                        <li><a href="{{URL_COURSES_DASHBOARD}}">{{ getPhrase('master_setup_dashboard')}}</a></li>
                        <li><a href="{{URL_MASTERSETTINGS_COURSE}}">{{ getPhrase('courses')}}</a></li>
                        <li class="active">{{isset($title) ? $title : ''}}</li>
                    </ol>
                </div>
            </div>
        @include('errors.errors')
        <!-- /.row -->

            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{URL_MASTERSETTINGS_COURSE}}"
                           class="btn  btn-primary button">{{ getPhrase('list')}}</a>
                    </div>
                    <h1>{{ $title }}  </h1>
                </div>
                <div class="panel-body" id="app">

                    <?php $button_name = getPhrase('update'); ?>
                    {{ Form::Open(
                    array('url' => ['mastersettings/course/editSemisters'],
                    'method'=>'patch')) }}
                    {{--@foreach($records as $record)--}}
                    {{--<fieldset class="form-group">

                        --}}{{--{{ Form::label(getPhrase('year'.$record->year), getPhrase(ucfirst('year '.$record->year))) }}--}}{{--
                        {{ Form::label(getPhrase('total_semesters')) }}

                        {{ Form::text($record->id, $value = $record->total_semisters , $attributes = array('id'=>'total_semesters','class'=>'form-control', 'placeholder' => '2')) }}
                    </fieldset>--}}
                    <input name="course_semester" type="hidden" value="{{$record_semester->id}}">
                    <fieldset class="form-group">
                        <label>{{getPhrase("currernt_semester")}}</label><br>
                        {{--<select class="form-control" name="current_semester" id="current_sem_selection">--}}
                        {{-- @for($i=1;$i<=$record->total_semisters;$i++)
                             <option value="{{$i}}">{{$i}}</option>
                         @endfor--}}
                        <select class="form-control" name="current_semester">
                            <option value="1" @if($record_semester->current_semester == 1) selected @endif>1</option>
                            <option value="2" @if($record_semester->current_semester == 2) selected @endif>2</option>
                        </select>
                        {{-- </select>--}}
                    </fieldset>
                    {{--@endforeach--}}

                    <div class="buttons text-center">
                        <button class="btn btn-lg btn-primary button">{{ $button_name }}</button>
                    </div>

                    {!! Form::close() !!}


                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
    {{--<script>
        $(document).ready(function () {
            $('#total_semesters').on('change', function () {
                $('#current_sem_selection').html('');
                for (var i = 1; i <= $(this).val(); i++) {
                    $('#current_sem_selection').append('<option value="' + i + '">' + i + '</option>');
                }
            });
        })
    </script>--}}
@stop

 