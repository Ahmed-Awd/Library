<script src="{{url('/js/jquery.min.js')}}"></script>
<fieldset class="form-group">

    {{ Form::label('academic_year_title', getphrase('academic_title')) }}
    <span class="text-red">*</span>

    {{ Form::text('academic_year_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '2016-2017 CSE',

                 'ng-model'=>'academic_year_title',

                'required'=> 'true',

                'ng-class'=>'{"has-error": formAcademics.academic_year_title.$touched && formAcademics.academic_year_title.$invalid}',

                'ng-minlength' => '4',

                'ng-maxlength' => '40',

                )) }}

    <div class="validation-error" ng-messages="formAcademics.academic_year_title.$error">

        {!! getValidationMessage()!!}

        {!! getValidationMessage('minlength')!!}

        {!! getValidationMessage('maxlength')!!}

    </div>
</fieldset>

<div class="row">
    <fieldset class="form-group col-md-6">

        {{ Form::label('academic_start_date', getphrase('start_date')) }}

        <span class="text-red">*</span>

        {{ Form::text('academic_start_date', null , $attributes = array('class'=>'form-control', 'placeholder' => '2015/7/17', 'id'=>'dp1',

                      'ng-model'=>'academic_start_date',

                 )) }}


    </fieldset>

    <fieldset class="form-group col-md-6">

        {{ Form::label('academic_end_date', getphrase('end_date')) }}

        <span class="text-red">*</span>

        {{ Form::text('academic_end_date', null , $attributes = array('class'=>'form-control', 'placeholder' => '2015/7/17', 'id'=>'dp2',

                      'ng-model'=>'academic_end_date',


                    )) }}

    </fieldset>
    {{-- <fieldset class="form-group col-md-6">

         {{ Form::label('current_semester', getphrase('current_semester')) }}

         <span class="text-red">*</span>
         <select class="form-control" name="current_semester">
             @if(isset($record) and !empty($record))
                 <option value="1" @if($record->current_semester == 1) selected @endif>1</option>
                 <option value="2" @if($record->current_semester == 2) selected @endif>2</option>
             @else
                 <option value="1">1</option>
                 <option value="2">2</option>
             @endif
         </select>

     </fieldset>--}}
    {{--<fieldset class="form-group col-md-6">
        {{ Form::label(getPhrase('total_semesters')) }}
        {{ Form::text('total_semesters', isset($record->total_semesters)?$record->total_semesters: null , $attributes = array('class'=>'form-control','id'=>'total_semesters')) }}
    </fieldset>--}}
    <fieldset class="form-group col-md-6">
        <a id="add-semester" class="btn btn-primary">{{getphrase('add_semester')}}</a>
    </fieldset>
    <div id="start_end">
        @if(isset($relatedSemesters))
            <?php $i = 1;?>
            @foreach($relatedSemesters as $item)
                <fieldset class="form-group col-md-12" id="timeEdit{{$i}}">
                    <fieldset class="form-group col-md-4">
                        {{ Form::label("semester_start_date", getphrase("semester_start_date").$item->sem_num) }}

                        <span class="text-red">*</span>

                        {{ Form::text('semester_start_date[]' ,$item->sem_start_date, $attributes = array('class'=>'form-control', 'placeholder' => '2015/7/17', 'id'=>'dp1')) }}

                    </fieldset>

                    <fieldset class="form-group col-md-4">

                        {{ Form::label('semester_end_date', getphrase("semester_end_date").$item->sem_num) }}

                        <span class="text-red">*</span>

                        {{ Form::text('semester_end_date[]', $item->sem_end_date , $attributes = array('class'=>'form-control', 'placeholder' => '2015/7/17', 'id'=>'dp2')) }}

                    </fieldset>
                    <fieldset class="form-group col-md-4">
                        <a id="timeEditButton{{$i}}" class="btn btn-danger"
                           style="margin-top:31px;">{{getPhrase('delete')}}</a>
                    </fieldset>
                </fieldset>
                <?php $i++;?>
            @endforeach
        @endif
    </div>
    <fieldset class='form-group col-md-6'>
        {{ Form::label('show_in_list', getphrase('show_in_list')) }}
        <div class="form-group row ">
            <div class="col-md-6 helper_step1">
                {{ Form::radio('show_in_list', 0, true, array('id'=>'free1', 'name'=>'show_in_list')) }}

                <label for="free1"> <span class="fa-stack radio-button"> <i
                                class="mdi mdi-check active"></i> </span> {{getPhrase('No')}}</label>
            </div>
            <div class="col-md-6">
                {{ Form::radio('show_in_list', 1, false, array('id'=>'paid1', 'name'=>'show_in_list')) }}
                <label for="paid1"> <span class="fa-stack radio-button"> <i
                                class="mdi mdi-check active"></i> </span> {{getPhrase('Yes')}} </label>
            </div>
        </div>
    </fieldset>

</div>

<div class="buttons text-center">

    <button class="btn btn-lg btn-primary button"

            ng-disabled='!formAcademics.$valid'>{{ $button_name }}</button>

</div>
<script>
    $(document).ready(function () {
        $('[id*=timeEditButton]').on('click', function () {
            var parentId = $(this).parent().parent().attr('id');
            $("#" + parentId).remove();
        })
        /* var start_end = $('#start_end').html();
         $('#start_end').html('');*/
        var i = "{{isset($relatedSemesters) ? count($relatedSemesters):0}}";
        $(document).on('click', '#add-semester', function () {
            i++;
            /*$('#start_end').html('');*/
            /*for (var i = 1; i <= $(this).val(); i++) {*/
            var t = '<fieldset class="form-group col-md-12" id="time' + i + '"><fieldset class="form-group col-md-4">' +
                '<label>{{getPhrase("semester_start_date")}}' + i + '</label>' +
                '<span class="text-red">*</span>' +
                '{{ Form::text("semester_start_date[]", null , $attributes = array("required"=>"required","class"=>"form-control", "placeholder" => "2015/7/17", "id"=>"dp3")) }}' +
                '</fieldset>' +
                '<fieldset class="form-group col-md-4">' +
                '<label>{{getPhrase("semester_end_date")}}' + i + '</label>' +
                '<span class="text-red">*</span>' +
                '{{ Form::text("semester_end_date[]", null , $attributes = array("required"=>"required","class"=>"form-control", "placeholder" => "2015/7/17", "id"=>"dp4")) }}' +
                '</fieldset>' +
                '<fieldset class="form-group col-md-4">' +
                '<a id="' + i + '" class="btn btn-danger" style="margin-top:31px;">{{getPhrase('delete')}}</a>' +
                '</fieldset>' +
                '</fieldset>';
            $(document).on('focus', '#dp3', function () {
                $('#dp3, #dp4').datetimepicker({
                    format: 'YYYY-MM-DD',
                });
            });
            $('#start_end').append(t);
            /*}*/
            $(document).on('click', 'a', function () {
                $('#time' + $(this).attr('id')).remove();
            });

        });
    })
</script>
