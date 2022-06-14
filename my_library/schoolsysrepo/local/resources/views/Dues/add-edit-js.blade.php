<script>
    $(document).ready(function () {
        $(document).on('change', '#academic_year', function () {
            $.ajax({
                type: 'get',
                url: '{{url('academic-courses/get-parent-courses')}}',
                data: {academic_id: $(this).val()},
                success: function (result) {
                    $('.course').css('visibility','visible')
                    $('#course_parent').empty();
                    $('#course_parent').append("<option value='select'>{{getPhrase('select')}}</option>");
                    result.forEach(function (n) {

                        var dataAppend = "<option value=" + n.course.id + ">" + n.course.course_title + "</option>"
                        $('#course_parent').append(dataAppend)
                    })

                }
            })
        })
        $(document).on('change', '#course_parent', function () {
            if ($('#academic_year').val() !== 'select' && $('#course_parent').val() !== 'select') {
                $('#add_new_expenses').css('visibility', 'visible')
            }
            $.ajax({
                type: 'get',
                url: '{{url('mastersettings/dues/get-all-expensesRelated')}}',
                data: {
                    'academic_id': $('#academic_year').val(),
                    'course_parent': $(this).val()
                },
                success: function (result) {
                    $('#expenses_body').empty()
                    $('#expenses_body').append(result)
                }
            })
        })
        $(document).on('click','.deleteExpenses',function () {
            $(this).parent().parent().parent().remove();
        })
    })

    function appendExpenses() {
        $.ajax({
            type: 'get',
            url: '{{url('mastersettings/dues/get-element-expenses')}}',
            data: {'kind': 'emptyFields'},
            success: function (result) {
                $('#expenses_body').append(result)
            }
        })
    }
</script>