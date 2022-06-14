<?php $tool_tip = '';
if (isset($value->tool_tip))
    $tool_tip = $value->tool_tip;
?>
<div class="col-md-6">
    <div class="form-group">
        <?php $key_name = substr($key, strpos($key, '-') + 1); ?>
        {{ Form::label($key,  getPhrase($key))  }}
        <select name="{{$key}}[value]" class="form-control" data-toggle="tooltip"
                title="{{$tool_tip}}"
                data-placement="right">


            @foreach($value->extra->options as $val=>$text)
                <?php
                $selected = '';
                if ($value->value == $val)
                    $selected = 'selected';
                ?>
                @if($text=='Template 1' or $text=='Template 2')
                    <option value="{{$val}}" {{$selected}}>{{getPhrase(str_replace(' ','_',$text))}}</option>
                @else
                    <option value="{{$val}}" {{$selected}}>{{getPhrase(str_replace(' ','_',$text))}}</option>
                @endif
            @endforeach
        </select>


        <input type="hidden" name="{{$key}}[type]" value="{{$value->type}}">
        <input type="hidden" name="{{$key}}[tool_tip]" value="{{$value->tool_tip}}">


    </div>
</div>