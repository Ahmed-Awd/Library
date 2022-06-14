<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <style>
        {
            border: 1px solid #555
        ;
            padding: 10px
        ;
            border-bottom: 1px solid #555
        ;
            margin: 0
        ;
            font-family: arial
        ;
            padding: 5px
        ;
            background: #4ca8f5
        ;
            color: #fff
        ;
        }
    </style>
</head>
<script src="{{JS}}jquery.min.js"></script>
<script src="{{JS}}jsBarCode.min.js"></script>
<link rel="stylesheet" href="{{CSS}}bootstrap.css">
<body>
<div id="DivIdToPrint">
    @for($std=0; $std < count($users_list);$std++)

        <table cellpadding="0" cellspacing="0" border="0" align="center" width="450"
               style=" font-family: arial; color:#555;" @if($lang==1) dir="rtl" @else dir="ltr" @endif>

            <tr>
                <td style="border: 1px solid #ddd;padding: 10px; border-bottom: 1px solid #ddd" align="center"><img
                            src="{{IMAGE_PATH_SETTINGS.getSetting('top_logo', 'id_card_settings')}}" height="40" alt="">
                    <p style="margin: 0; color:#777">{{getSetting('institute_address','id_card_settings')}}</p>
                </td>
            </tr>
            <tr>
                <td style=" border: 1px solid #ddd;padding: 10px;" width="100%">
                    <table cellpadding="0" cellspacing="0" border="0" style="">
                        <tr>
                            <td width="120px"><img src="{{getProfilePath($users_list[$std]['image'],'profile')}}"
                                                   width="110px"
                                                   alt=""></td>
                            <td style="padding: 0 15px 0 15px;" valign="top">
                                <table cellpadding="0" cellspacing="0" border="0">

                                    @for($i=0; $i< $settings['31-front_total_fields']['value'];$i++)
                                        @if($i == count($front_title))
                                            @break;
                                        @endif
                                        @if($settings[$front[$i]]['value'] !== 'skip')
                                            <tr>
                                                <td style="padding: 5px;">
                                                    <strong>
                                                        {{getphrase($settings[$front_title[$i]]['value'])}}
                                                    </strong>
                                                </td>
                                                <td style="padding: 5px;" width="1%">:</td>
                                                <td style="padding: 5px;">
                                                    {{$users_list[$std][$settings[$front[$i]]['value']]}}
                                                </td>

                                            </tr>
                                        @endif
                                    @endfor
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="background: #4ca8f5; color: #fff;">
                    <table cellpadding="0" cellspacing="0" border="0" style="">
                        <tr>
                            <td valign="middile" height="30px" width="120px" align="center"
                                style="background: #fff; color: #4ca8f5; border:1px solid #4ca8f5;">
                                <strong>
                                    <div class="idcard-id">{{$users_list[$std]['roll_no']}}</div>
                                </strong></td>

                            <td style="padding: 5px;">{{getPhrase('emergency_no')}}
                                .{{$users_list[$std]['home_phone']}}</td>

                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            <tr>
                <td>
                    &nbsp;
                </td>
            </tr>
            <table cellpadding="0" cellspacing="0" border="0" align="center" width="450"
                   style="border: 1px solid #ddd; font-family: arial; color:#555;" @if($lang==1) dir="rtl" @else dir="ltr" @endif>

                <tr>
                    <td colspan="8" style="padding: 10px; border-bottom: 1px solid #ddd" align="center"><img
                                src="{{IMAGE_PATH_SETTINGS.getSetting('top_logo', 'id_card_settings')}}" height="40"
                                alt="">

                        <p style="margin: 0; color:#777">{{getSetting('institute_address','id_card_settings')}}</p>

                    </td>

                </tr>
                @for($i=0; $i<count($back_title);$i++)
                    @if($settings[$back_title[$i]]['value'] !== '')
                        <tr>
                            <td style="padding: 8px;">
                                <strong>{{getphrase($settings[$back_title[$i]]['value'])}}
                                    : </strong> {{$settings[$back[$i]]['value']}}
                            </td>
                        </tr>
                    @endif
                @endfor
                @if($users_list[$std]['id_number'] != null)
                <tr>
                    <td style="padding: 8px;" class="text-right">
                            {{--<h3>{{$users_list[$std]['id_number']}}</h3>--}}
                        <svg class="barcode"
                             jsbarcode-format="CODE128"
                             jsbarcode-value="{{$users_list[$std]['id_number']}}"
                             jsbarcode-textmargin="0"
                             jsbarcode-fontoptions="bold"
                        >
                        </svg>
                        <script>
                            JsBarcode(".barcode").init();
                        </script>
                    </td>
                </tr>
                    @endif


            </table>
        </table>
        <br>
    @endfor
</div>
</body>
<script>
    function printDiv() {

        var divToPrint = document.getElementById('DivIdToPrint');

        var newWin = window.open('', 'Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

        newWin.document.close();

        setTimeout(function () {
            newWin.close();
        }, 10);

    }
</script>

</html>