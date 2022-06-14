<script>


    $(document).ready(function () {
        $('#course_selection').on('change', function () {
            getSubjects();
        })
        $(document).on('click', '.new', function (e) {
            e.preventDefault();
            $('.texts').append('<div class="row">' +
                '                <fieldset class="form-group col-md-11">' +
                '                    {{ Form::label('skill', getphrase('skill')) }}' +
                '                    <span class="text-red">*</span>' +
                '                    {{ Form::text('skills[]', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Skill Name','id'=>'skill')) }}' +
                '                </fieldset>' +
                '                <fieldset class="form-group col-md-1" style="margin-top: 35px;">' +
                '                    <button class="btn btn-danger">{{getPhrase('delete')}}</button>' +
                '                </fieldset>' +
                '            </div>');
        });
        $(document).on('click', '.btn-danger', function (e) {
            e.preventDefault();
            $(this).parent().parent().remove();
        })
    })
</script>