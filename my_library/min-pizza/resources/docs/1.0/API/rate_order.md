##تقييم الطلبية

<api-ref title="rating the order" verb="post" route="rate-order/{order}" :response-codes="[200]">
    <template v-slot:description>
        تقييم طلبية بعد ان وصلت بساعة على الأقل 
    </template>
    <template v-slot:body>
        <api-ref-item name="rate" :required="true" type="number">
            number from 1 to 6 
        </api-ref-item>
        <api-ref-item name="comment" :required="false" type="string">
             your comment on the order
        </api-ref-item>
    </template>
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": variable,
}
        </pre>
    </template>

</api-ref>
