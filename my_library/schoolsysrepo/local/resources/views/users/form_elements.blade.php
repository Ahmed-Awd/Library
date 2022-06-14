<fieldset class="form-group">
<?php $readonlyForParents = ''; 
if(checkRole(['parent']))
	$readonlyForParents = 'readonly="true"';

?>
    {{ Form::label('name', getphrase('name')) }}

    <span class="text-red">*</span>

    {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getphrase('name'),

        'ng-model'=>'name',
        
        'required'=> 'true',
         $readonlyForParents,
        'ng-pattern' => getRegexPattern('name'),
        'ng-class'=>'{"has-error": formUsers.name.$touched && formUsers.name.$invalid}',

      )) }}

    <div class="validation-error" ng-messages="formUsers.name.$error">

        {!! getValidationMessage()!!}

        {!! getValidationMessage('pattern')!!}

    </div>

</fieldset>


<?php

$readonly = '';

$username_value = null;

if ($record) {

    $readonly = 'readonly="true"';

    // $username_value = $record->username;

}



?>

<fieldset class="form-group">

    {{ Form::label('ID_number', getphrase('ID_number')) }}
    <span class="text-red">*</span>

    {{ Form::text('id_number', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '1234567890',

    'ng-model'=>'id_number',

    'required'=> 'true',
	 $readonlyForParents,

    'ng-minlength' => '10',

    'ng-maxlength' => '10',

    'oninput' => "this.value = this.value.replace(/[^0-9]/, '')"

  )) }}



</fieldset>

<fieldset class="form-group">


    {{ Form::label('username', getphrase('username')) }}

    <span class="text-red">*</span>

    {{ Form::text('username', $value = $username_value , $attributes = array('class'=>'form-control', 'placeholder' => getphrase('name'),

        'ng-model'=>'username',

        'required'=> 'true',

         $readonly,



        'ng-minlength' => '2',

        'ng-maxlength' => '20',

        'ng-class'=>'{"has-error": formUsers.username.$touched && formUsers.username.$invalid}',



    )) }}

    <div class="validation-error" ng-messages="formUsers.username.$error">

        {!! getValidationMessage()!!}

        {!! getValidationMessage('minlength')!!}

        {!! getValidationMessage('maxlength')!!}

        {!! getValidationMessage('pattern')!!}

    </div>

</fieldset>


<fieldset class="form-group">

    <?php

    $readonly = '';

//    if (!checkRole(getUserGrade(4)))
//
//        $readonly = 'readonly="true"';
//
//    if ($record) {
//
//        $readonly = 'readonly="true"';
//
//    }



    ?>

    {{ Form::label('email', getphrase('email')) }}

    <span class="text-red">*</span>

    {{ Form::email('email', $value = null, $attributes = array('class'=>'form-control', 'placeholder' => 'email@sasbit.com',

        'ng-model'=>'email',

        'required'=> 'true',

        'ng-class'=>'{"has-error": formUsers.email.$touched && formUsers.email.$invalid}',

     $readonly)) }}

    <div class="validation-error" ng-messages="formUsers.email.$error">

        {!! getValidationMessage()!!}

        {!! getValidationMessage('email')!!}

    </div>

</fieldset>


<fieldset class="form-group">

    {{ Form::label('password', getphrase('password')) }}

    <span class="text-red">*</span><br>
    @if($title == 'Add User')
        {{ Form::password('password',['class'=>'form-control','ng-model'=>'password','required'=>'required']) }}
    @else
        {{ Form::password('password',['class'=>'form-control','ng-model'=>'password']) }}
    @endif
</fieldset>
<fieldset class="form-group">

    {{ Form::label('password_confirm', getphrase('confirm_password')) }}

    <span class="text-red">*</span><br>
    @if($title == 'Add User')
        {{ Form::password('password_confirm',['class'=>'form-control','ng-model'=>'password_confirm','required'=>'required']) }}
    @else
        {{ Form::password('password_confirm',['class'=>'form-control','ng-model'=>'password_confirm']) }}
    @endif

</fieldset>
@if(checkRole(getUserGrade(2)) && Module_state('branches'))
<fieldset class="form-group">
    {{ Form::label('school_branch', getphrase('school_branch')) }}

    <span class="text-red">*</span>

    {{Form::select('branch', $branches, $default_branch, ['placeholder' => getPhrase('select_branch'),'class'=>'form-control',


    'required'=> 'true',

 ])}}
</fieldset>
@endif
@if($record != false)
   
<fieldset class="form-group">
    {{ Form::label('category', getphrase('category')) }}

    <span class="text-red">*</span>

    {{Form::select('category_id', $categories, $record->category_id, ['placeholder' => getPhrase('select_category'),'class'=>'form-control',


    'required'=> 'true',

 ])}}
</fieldset>
       
@endif
@if($record == false)
   
<fieldset class="form-group">
    {{ Form::label('category', getphrase('category')) }}

    <span class="text-red">*</span>

    {{Form::select('category_id', $categories, Auth::user()->category_id, ['placeholder' => getPhrase('select_category'),'class'=>'form-control',


    'required'=> 'true',

 ])}}
</fieldset>
       
@endif


@if(Module_state('language_settings'))
<fieldset class="form-group">
    {{ Form::label('default_language', getphrase('default_language')) }}

    <span class="text-red">*</span>

    {{Form::select('default_lang', $languages, $default_lang, ['placeholder' => getPhrase('select_language'),'class'=>'form-control',

    'ng-model'=>'default_language',
    'id'=>'default_language',

    'required'=> 'true',

 ])}}
</fieldset>

@endif
@if(!checkRole(['parent']))

    <fieldset class="form-group">


        {{ Form::label('role', getphrase('role')) }}

        <span class="text-red">*</span>

        <?php $disabled = (checkRole(getUserGrade(2))) ? '' : 'disabled';



        $selected = getRoleData('student');

        if ($record)

            $selected = $record->role_id;

        ?>

        {{Form::select('role_id', $roles, $selected, ['placeholder' => getPhrase('select_role'),'class'=>'form-control', $disabled,

            'ng-model'=>'role_id',
            'id'=>'role_id',

            'required'=> 'true',

            'ng-class'=>'{"has-error": formUsers.role_id.$touched && formUsers.role_id.$invalid}'

         ])}}

        <div class="validation-error" ng-messages="formUsers.role_id.$error">

            {!! getValidationMessage()!!}


        </div>


    </fieldset>

@endif



<fieldset class="form-group">


    {{ Form::label('phone', getphrase('phone')) }}

    <span class="text-red">*</span>

    {{ Form::number('phone', $value = null , $attributes = array('class'=>'form-control', 'placeholder' =>
    getPhrase('please_enter_10-15_digit_mobile_number'),

        'ng-model'=>'phone',

        'required'=> 'true',

        'ng-pattern' => getRegexPattern("phone"),

        'ng-class'=>'{"has-error": formUsers.phone.$touched && formUsers.phone.$invalid}',


    )) }}


    <div class="validation-error" ng-messages="formUsers.phone.$error">

        {!! getValidationMessage()!!}

        {!! getValidationMessage('phone')!!}

        {!! getValidationMessage('maxlength')!!}

    </div>

</fieldset>

<div class="row">

    <fieldset class="form-group col-sm-6">


        {{ Form::label('address', getphrase('billing_address')) }}



        {{ Form::textarea('address', $value = null , $attributes = array('class'=>'form-control','rows'=>3, 'cols'=>'15', 'placeholder' => getPhrase('please_enter_your_address'),

            'ng-model'=>'address',

            )) }}

    </fieldset>


    <fieldset class='col-sm-6'>

        {{ Form::label('image', getphrase('image')) }}

        <div class="form-group row">

            <div class="col-md-6">


                {!! Form::file('image', array('id'=>'image_input', 'accept'=>'.png,.jpg,.jpeg')) !!}

            </div>

            <?php if(isset($record) && $record) {

            if($record->image != '') {

            ?>

            <div class="col-md-6">

                <img src="{{ getProfilePath($record->image) }}"/>


            </div>

            <?php } } ?>

        </div>

    </fieldset>

</div>


<div class="buttons text-center">

    <button class="btn btn-lg btn-primary button"

            ng-disabled='!formUsers.$valid'>{{ $button_name }}</button>

</div>
