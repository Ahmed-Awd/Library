@extends($layout)
@section('content')
    <div id="page-wrapper" ng-controller="couponsController">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a></li>
                        <li><a href="{{url('parent/purchase-expenses/all')}}">{{getPhrase('academic_expenses')}}</a>
                        </li>
                        <li>{{$record->name}}</li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h1>{{ $title }}</h1>
                </div>

                <div class="panel-body packages">
                    <?php
                    if (isset($dues_purchase)) {
                        $specifications = json_decode($dues_purchase->specifications, true);
                    }
                    $total = 0;
                    ?>

                    {{ Form::open(array('url' => 'parent/purchase-expenses/pay/'.$slug, 'method'=>'post')) }}
                    {{csrf_field()}}
                    <h1><b>-{{ getPhrase('mandatory_items') }}</b></h1>
                    @foreach($schoolExpenses as $expense)
                        @if($expense->due_type == 'mandatory')
                            <input type="hidden" name="mand[]" value="{{$expense->due_value.'/'.$expense->title}}">
                            <?php
                            if (isset($specifications) and !in_array($expense->title, $specifications['dues_title'])) {
                                $total += $expense->due_value;
                            }
                            if (!isset($specifications)) {
                                $total += $expense->due_value;
                            }
                            ?>
                            <label for="{{$expense->id}}">
                                <span> <i class=""></i> </span>
                                <b style="color: red;">{{$expense->title}}</b> :
                                <b style="color: red;">{{$expense->due_value}}</b>
                            </label>
                            <br>
                            <small
                                    class="text-danger">@if(isset($dues_purchase) and in_array($expense->title,$specifications['dues_title'])) {{ getPhrase('payed_before')}}@endif</small>
                            <br><br>
                        @endif
                    @endforeach
                    <h1><b>-{{ getPhrase('optional_items') }}</b></h1>
                    @if(isset($schoolExpenses) and count($schoolExpenses) > 0)
                        @foreach($schoolExpenses as $expense)
                            @if($expense->due_type != 'mandatory')
                                <input type="checkbox" id="{{$expense->id}}" class="expenses" name="expenses[]"
                                       @if(isset($dues_purchase) and in_array($expense->title,$specifications['dues_title']))
                                       checked=""
                                       disabled
                                       @endif
                                       value="{{$expense->due_value.'/'.$expense->title}}">
                                <label for="{{$expense->id}}">
                                    <span class="fa-stack checkbox-button"> <i class="mdi mdi-check active"></i> </span>
                                    <b style="color: red;">{{$expense->title}}</b> :
                                    <b style="color: red;">{{$expense->due_value}}</b>
                                </label>
                                <br>
                                <small class="text-danger">@if(isset($dues_purchase) and in_array($expense->title,$specifications['dues_title'])) {{ getPhrase('payed_before')}}@endif</small>
                                <br><br>
                            @endif
                        @endforeach

                    @endif
                    <h3>{{getPhrase('total')}}: <span id="total">
                            @if(isset($specifications))
                                {{$total+$specifications['remain_purchase'] - $specifications['under_review']}}
                                <?php
                                $total = $total + $specifications['remain_purchase'];
                                ?>

                            @else
                                {{$total+0}}
                                <?php
                                $total = $total + 0;
                                ?>

                            @endif
                            
                        </span>
                    </h3>
                    <div class="row">
                        <fieldset class="form-group col-md-3">
                            {{ Form::label('your_money', getphrase('your_money')) }}
                            <span class="text-red">*</span>
                            {{ Form::number('your_money', $value = null , $attributes = array('class'=>'form-control','required'=>'required','max'=>$total,'placeholder' => getPhrase('your_money'),'id'=>'your_money')) }}
                        </fieldset>
                    </div>
                    @if(Module_state('coupons') && 1==0 /* copuons disabled */) 
                        <div class="row">
                            <fieldset class="form-group col-md-3">
                                <input type="text" id="coupon_text"
                                       class="form-control apply-input-lg"
                                       placeholder="{{getPhrase('enter_coupon_code')}}">

                                <span class="input-group-btn">
              								<button class="btn btn-success button apply-input-button"
                                                    onclick="validateCoupon()"
                                                    type="button">{{getPhrase('apply')}}</button>

                                     </span>
                                <span id="to_be_copoun">
                                     </span>
                            </fieldset>
                        </div>
                    @endif
                    <h3>{{getPhrase('the_amount_paid')}}: <span
                                style="color: red;">{{ isset($dues_purchase) ? $specifications['your_money'] : '-'}}</span><br>
                        <h3>{{getPhrase('the_amount_under_review')}} : <span
                        style="color: red;">{{ isset($dues_purchase) && isset($specifications['under_review']) && !empty($specifications['under_review']) ? $specifications['under_review'] : '-'}}</span><br>
                    
                        {{getPhrase('at_time')}}: <span
                                style="color: red;">{{isset($dues_purchase) ? $dues_purchase->updated_at : '-'}}</span>
                    </h3>
                    <input type="hidden" value="" id="gateway" name="gateway">
                    <input type="hidden" name="coupon" id="coupon" value="0">
                    <div style="margin-right: 300px;">
                        @if($settingsModule->payu->value == 1)

                            <button type="submit"
                                    class="btn-lg btn button btn-card" id="payu"><i
                                        class=" icon-credit-card"></i> {{getPhrase('payu')}}</button>
                        @endif

                        @if($settingsModule->paypal->value == 1 && 1 == 0/* paypal is disabled */)
                            <button type="submit" class="btn-lg btn button btn-paypal" id="paypal"><i
                                        class="icon-paypal"></i> {{getPhrase('paypal')}}</button>
                        @endif
                        @if($settingsModule->offline_payment->value == 1)
                            <button type="submit" class="btn-lg btn button btn-info" id="offline"
                                    title="{{ getPhrase('click_here_to_update_payment_details') }}"><i
                                        class="fa fa-money"></i> {{getPhrase('offline_payment')}}
                            </button>

                        @endif
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer_scripts')
    @include('common.alertify')
    @include('Dues.js-script',array('student_id'=>$record->id))
@stop
