<?php
    $student = getStudentInfo(Auth::user()->slug);
?>

<div class="panel-heading">
    <h2>{{getPhrase('User_Profile')}}</h2></div>
<div class="panel-body">
    <div class="row">
        <div class="profile-details text-center">
            <div class="profile-img">
            @if(Auth::user()->image != null || Auth::user()->image != '')
                <img class="thumb" src="{{IMAGE_PATH_PROFILE}}{{Auth::user()->image}}" height="60">
            @else
                    <img class="thumb" src="{{IMAGE_PATH_USERS_DEFAULT_THUMB}}">
            @endif
            </div>
            <div class="aouther-school">
                <h2>{{Auth::user()->name}}</h2>
                <p><span>({{$student['current_academic_year']}})<span>{{$student['current_class']}}</span></span></p>
            </div>

        </div>
    </div>
    </hr>
    <table class="table">
        <tbody>
        <tr>
            <th>{{getPhrase('current_academic_branch')}}:</th>
            <th>{{get_branch_name(Auth::user()->branch_id)}}</th>
        </tr>
        <tr>
            <th>{{getPhrase('current_category')}}:</th>
            <th>{{get_category_name(Auth::user()->category_id)}}</th>
        </tr>
        <tr>
            <th>{{getPhrase('current_academic_year')}}:</th>
            <th>{{$student['current_academic_year']}}</th>
        </tr>
        <tr>
            <th>{{getPhrase('current_semester')}}:</th>
            <th>{{getPhrase($student['current_semester'])}}</th>
        </tr>
        <tr>
            <th>{{getPhrase('current_grade')}}:</th>
            <th>{{$student['current_grade']}}</th>
        </tr>
        <tr>
            <th>{{getPhrase('current_class')}}:</th>
            <th>{{$student['current_class']}}</th>
        </tr>
        </tbody>
    </table>


</div>