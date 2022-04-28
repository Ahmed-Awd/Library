#التقارير

###تقارير المطعم
<api-ref title="reports of owner" verb="get" route="/api/owner/my-reports" :response-codes="[200]">
    <template v-slot:description>
    تقراير صاحب المطعم  
    </template>
     <template v-slot:headers>
        <api-ref-item name="accept-language" :required="false" type="string" example="application/json">
            accept language (ar, en ,tr)
        </api-ref-item>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
        <api-ref-item name="from" :required="true" type="date" example="2021-09-22">
            the from date of orders
        </api-ref-item>
        <api-ref-item name="to" :required="true" type="date" example="2021-09-22">
            the to date of orders
        </api-ref-item>
        <api-ref-item name="order_status" :required="false" type="array" example="[1,2,3,4]">
            array of status codes
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "orders": []
}
        </pre>
    </template>

</api-ref>



###تقارير السائق
<api-ref title="reports of driver" verb="get" route="/api/driver/my-reports" :response-codes="[200]">
    <template v-slot:description>
    تقراير السائق  
    </template>
     <template v-slot:headers>
        <api-ref-item name="accept-language" :required="false" type="string" example="application/json">
            accept language (ar, en ,tr)
        </api-ref-item>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
        <api-ref-item name="from" :required="true" type="date" example="2021-09-22">
            the from date of orders
        </api-ref-item>
        <api-ref-item name="to" :required="true" type="date" example="2021-09-22">
            the to date of orders
        </api-ref-item>
        <api-ref-item name="order_status" :required="false" type="array" example="[1,2,3,4]">
            array of status codes
        </api-ref-item>
        <api-ref-item name="rate" :required="false" type="numeric" example="3">
            number between 1 and 5
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "orders": []
}
        </pre>
    </template>

</api-ref>
