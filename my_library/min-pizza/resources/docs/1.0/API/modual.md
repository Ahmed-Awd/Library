#الأعدادات العرض

يوجد اعدادات بحيث يتم عرض او اخفائه في العرض

 الاحصائية .


##جلب أعدادات 

<api-ref title="get all modules " verb="get" route="/api/module/" :response-codes="[200]">
    <template v-slot:description>
    جلب كل الأعدادات  العرض  من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'modules' => {array},
}
</pre>
</template>
</api-ref>

##جلب أعداد معين 

<api-ref title="get specific module  " verb="post" route="/api/module/{key}" :response-codes="[200]">
    <template v-slot:description>
    جلب كل أعداد  معين 
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'module' => {object},
}
</pre>
</template>
</api-ref>

##تحديث الأعدادات

<api-ref title="update the general settings" verb="post" route="/api/general-settings" :response-codes="[200]">
    <template v-slot:description>
        تحديث كل الأعدادات العرض
    </template>
    <template v-slot:body>
        <api-ref-item name="modules" :required="true" type="array">
            an array which the key the key of the module and the value is it's value
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'updating process completed',
}
</pre>
</template>
</api-ref>

