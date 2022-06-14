<script>
    $(document).ready(function () {
        var total = parseInt($('#total').text());
        $('#your_money').val(total)
        $('.expenses').on('change', function () {
            if ($(this).prop('checked') == true) {
                total = total + parseInt($(this).val());
                $('#total').text(total)
                $('#your_money').val(total)
                $('#your_money').attr('max',total)
            }
            if ($(this).prop('checked') == false) {
                total = total - parseInt($(this).val());
                $('#total').text(total)
                $('#your_money').val(total)
                $('#your_money').attr('max',total)
            }
        })
        $('button').on('click', function () {
            $('#gateway').val($(this).attr('id'))
        })

    })

    function validateCoupon() {
        $.ajax({
            url: "{{url('coupons/validate-coupon')}}",
            type: "get",
            data: {
                'item_name': '',
                'item_type': 'academic_expenses',
                'cost': $('#total').text(),
                'coupon_code': $('#coupon_text').val(),
                'student_id': "{{$student_id}}"
            },
            success: function (result) {
                var resultParsed = JSON.parse(result)
                if (resultParsed.status == 0) {
                    alertify.error(resultParsed.message);
                    return;
                }
                if (resultParsed.status == 1) {
                    $('.apply-input-button').prop('disabled', true)
                    if (resultParsed.type == "value") {
                        var total = parseInt($('#total').text().trim()) - parseInt(resultParsed.discount)
                        $('#total').text(total.toString())
                        $('#your_money').val(total.toString())
                        $('#your_money').attr('max', total)
                        $('#to_be_copoun').text("{{getPhrase('discount_is')}}" + resultParsed.discount)
                        $('#coupon').val(resultParsed.discount);
                    }else{
                        console.log(resultParsed)
                        var total = parseInt($('#total').text().trim())
                        var discount= parseInt(resultParsed.discount)
                        var totalAfterDiscount=total - ((total * discount) / 100)
                        var difference=total-totalAfterDiscount;
                        $('#total').text(totalAfterDiscount.toString())
                        $('#your_money').val(totalAfterDiscount.toString())
                        $('#your_money').attr('max', totalAfterDiscount)
                        $('#to_be_copoun').text("{{getPhrase('discount_is')}}" + resultParsed.discount +' % ' + "{{getPhrase('and_it_value')}}"+ ' '+difference)
                        $('#coupon').val(difference);
                    }
                    alertify.success(resultParsed.message);
                    return;
                }
            }
        })
    }
</script>

