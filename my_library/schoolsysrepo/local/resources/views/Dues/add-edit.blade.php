@extends($layout)
@section('content')
    <?php $due_types = array('select', 'mandatory', 'optional'); ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a></li>
                        <li><a href="{{url('/mastersettings/skills')}}">{{getPhrase('all_academic_dues')}}</a></li>
                        <li>{{$title}}</li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons helper_step1">
                        <a href="{{url('/mastersettings/dues/rapid_add')}}"
                           class="btn  btn-primary button">{{ getPhrase('create_expenses_items')}}</a>
                    </div>
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    {{ Form::open(array('url' => 'mastersettings/dues/store', 'method'=>'post')) }}
                    {{csrf_field()}}
                    <fieldset class="form-group col-md-4">
                        {{ Form::label('academic_year', getphrase('academic_year')) }}
                        <span class="text-red">*</span>
                        <select name="academic_year" class="form-control" id="academic_year" required="required">
                            <option value="select">{{getPhrase('select')}}</option>
                            @foreach($academics_years as $value)
                                <option value="{{$value->id}}"
                                        @if(isset($record) and $record->academic_id == $value->id) selected @endif>{{$value->academic_year_title}}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="form-group col-md-4 course" style="visibility: hidden;">
                        {{ Form::label('course_parent', getphrase('course_parent')) }}
                        <span class="text-red">*</span>
                        <select name="course_parent" class="form-control" id="course_parent" required="required">
                            <option value="select">{{getPhrase('select')}}</option>
                        </select>
                    </fieldset>
                    <div class="row">
                        <a class="btn btn-primary" onclick="appendExpenses()" id="add_new_expenses"
                           style="visibility: hidden; margin-top: 33px;">{{getPhrase('add_new_expenses')}}</a>
                    </div>
                    <br>
                    <div class="row" id="expenses_body">

                    </div>
                    <br>
                    <div class="row">
                        <fieldset class="form-group col-md-3">
                            <input type="submit" class="btn btn-success"
                                   style="margin-right: 600px; height: 70px; width: 70px;" value="{{getPhrase('save')}}">
                        </fieldset>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
@stop
@section('footer_scripts')
    @include('Dues.add-edit-js')
@stop
