@if(!isset($fillables))
    <div class="row">
        <div class="col-md-3">
            {{ Form::label('academic_dues', getphrase('academic_dues')) }}
            <span class="text-red">*</span>
            <select class="form-control dueName" name="due_title[]" required>
                @if(isset($allDues))
                    @foreach($allDues as $due_type)
                        <option value="{{$due_type->id}}" required>{{$due_type->title}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            {{ Form::label('due_value', getphrase('due_value')) }}
            <input type="number" class="form-control" name="due_value[]" required>
        </div>
        <div class="col-md-3">
            {{ Form::label('due_type', getphrase('due_type')) }}
            <select class="form-control" name="due_type[]" required>
                @if(isset($due_types))
                    @foreach($due_types as $due_type)
                        <option value="{{$due_type}}" required>{{getPhrase($due_type)}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <fieldset style="margin-top: 35px;">
                <a class="btn btn-danger deleteExpenses">{{getPhrase('delete')}}</a>
            </fieldset>
        </div>
    </div>
@else
    @foreach($fillables as $fillable)
        <div class="row">
            <div class="col-md-3">
                {{ Form::label('academic_dues', getphrase('academic_dues')) }}
                <span class="text-red">*</span>
                <select class="form-control dueName" name="due_title[]" required>
                    @if(isset($allDues))
                        @foreach($allDues as $due_type)
                            <option value="{{$due_type->id}}" @if($fillable->title == $due_type->title)  selected
                                    @endif required>{{$due_type->title}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                {{ Form::label('due_value', getphrase('due_value')) }}
                <input type="number" class="form-control" name="due_value[]" value="{{$fillable->due_value}}" required>
            </div>
            <div class="col-md-3">
                {{ Form::label('due_type', getphrase('due_type')) }}
                <select class="form-control" name="due_type[]" required>
                    @if(isset($due_types))
                        @foreach($due_types as $due_type)
                            <option value="{{$due_type}}"
                                    @if($fillable->due_type == $due_type) selected @endif>{{getPhrase($due_type)}}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <fieldset style="margin-top: 35px;">
                    <a class="btn btn-danger deleteExpenses">{{getPhrase('delete')}}</a>
                </fieldset>
            </div>
        </div>
    @endforeach
@endif