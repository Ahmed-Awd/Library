<?php $tool_tip = '';
if (isset($value->tool_tip))
    $tool_tip = $value->tool_tip;

?>
@if($social != 'view' or (in_array('facebook',explode('_',strtolower($key))) and $hideElementOrView['facebook_login']['value'] != 0) or (in_array('google',explode('_',strtolower($key))) and $hideElementOrView['google_plus_login']['value'] != 0))
    <div class="col-md-6">
        <fieldset class="form-group">
            @if(strpos($key, '-') == true)
                <?php $key_name = substr($key, strpos($key, '-') + 1); ?>
                {{ Form::label($key_name, getPhrase($key_name)) }}
            @else
                {{ Form::label($key, getPhrase($key)) }}
            @endif
            <input
                    type="{{$value->type}}"
                    class="form-control"
                    name="{{$key}}[value]"
                    required="true"
                    value="{{$value->value}}"
                    data-toggle="tooltip"
                    title="{{$tool_tip}}"
                    data-placement="right"
            >

            <input
                    type="hidden"
                    name="{{$key}}[type]"
                    value="{{$value->type}}">

            <input
                    type="hidden"
                    name="{{$key}}[tool_tip]"
                    value="{{$tool_tip}}">

        </fieldset>
    </div>
@endif