#  طرق دفع المطاعم 

###  ربط المطعم  بطريقة دفع

<api-ref title=" attach method  to restaurant data" verb="post" route="/api/restaurant/attach/{restaurant}/method" :response-codes="[200]">
    <template v-slot:description>
  ربط طريقة دفع  عندالمطعم   
 </template>
 <template v-slot:body>
 <api-ref-item name="method_id" :required="true" type="number">
            the method id is id of payment_methods
        </api-ref-item>
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "method payment attached to the restaurant successfully",
}
        </pre>
    </template>
</api-ref>

###  الغاء المطعم  لطريقة دفع

<api-ref title=" detach method  to restaurant data" verb="post" route="/api/restaurant/detach/{restaurant}/method" :response-codes="[200]">
    <template v-slot:description>
  الغاء طريقة دفع عند المطعم   
 </template>
 <template v-slot:body>
 <api-ref-item name="method_id" :required="true" type="number">
            the method id is id of payment_methods
        </api-ref-item>
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "method payment detached from the restaurant successfully",
}
        </pre>
    </template>
</api-ref>


###  جلب طرق الدفع

<api-ref title=" get payment method" verb="get" route="/api/payment-method" :response-codes="[200]">
    <template v-slot:description>
  جلب طرق الدفع   
 </template>
    <template v-slot:200>
        <pre>
{
    "methods": [array],
}
        </pre>
    </template>
</api-ref>
