# شحن الرصيد

##مقدمة

شحن الرصيد هو شئ أساسي فى التطبيق فكل من السائق و صاحب المطعم يحتاج رصيد ليتم عملية الطلب

الباقات و أكواد الشحن يتم أنشاءها عن طريق الادمن بانل و يقوم المستخدمين بشراء كروت و استخدام الأكواد الموجودة بها لشحن رصيدهم



##عملية الشحن

<api-ref title="recharge" verb="post" route="/api/charge" :response-codes="[200, 401]">
    <template v-slot:description>
كل المطلوب هوا أرسال كود الشحن عبر ال api التالية 
    </template>
    <template v-slot:body>
        <api-ref-item name="code" :required="true" type="string">
            The code 
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
    "message": "charging your balance success",
}
        </pre>
    </template>
    <template v-slot:401>
        <pre>
{
    "message": "Unauthorized"
}
        </pre>
    </template>
</api-ref>

##عرض الباقات
<api-ref title="packages" verb="GET" route="/api/packages" :response-codes="[200, 401]">
    <template v-slot:description>
كل المطلوب هوا أرسال كود الشحن عبر ال api التالية 
    </template>
    <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "packages": "array of packages"
}
        </pre>
    </template>
    <template v-slot:401>
        <pre>
{
    "message": "Unauthorized"
}
        </pre>
    </template>
</api-ref>
