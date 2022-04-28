# المدن

## مقدمة

المدن التى يعمل بها التطبيق



### جلب كل المدن
<api-ref title="get all towns" verb="get" route="/api/towns" :response-codes="[200]">
    <template v-slot:description>
    المدن  
    </template>
     <template v-slot:headers>
        <api-ref-item name="accept-language" :required="false" type="string" example="application/json">
            accept language (ar, en ,tr)
        </api-ref-item>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "towns": []
}
        </pre>
    </template>

</api-ref>
