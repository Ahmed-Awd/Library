#الأعدادات العامة

يوجد أعدادات عامة لتنظيم كل ما بتعلق بنظام التطبيق و الموقع


اسم التطبيق .
شعار التطبيق .
عناصر تواصل معنا ( email ، mobile  …. ).
روابط وسائل التواصل الإجتماعي .
تحديد المدة التي يتم تذكير الأدمن بالطلبات المجدولة قبل أن يحين وقت الطلب المجدول .
إدخال الوقت الذي يتم فيه إبلاغ الأدمن بأن هذا الطلب لم يتم اتخاذ أي إجراء فيه من قبول أو رفض من قبل المطعم .
ادخال الوقت الذي بعده يتم إلغاء الطلبات للمطاعم  و يصل إشعار للأدمن و للزبون أنه تم إلغاء الطلب.
خيار إلغاء رسوم التوصيل لجميع المطاعم .
تحديد وقت لاستقبال السائق للطلب و بعد هذا الوقت يتم إسناد الطلب لسائق آخر .
رقم الدعم الفني : يتم وضع رقم خاص بالإدارة للتواصل به في حال كان هناك خطأ .



##جلب أعدادات التطبيق

<api-ref title="get all settings " verb="get" route="/api/general-settings/" :response-codes="[200]">
    <template v-slot:description>
    جلب كل الأعدادات  العامة  من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'settings' => {array},
}
</pre>
</template>
</api-ref>

##جلب أعداد معين للتطبيق

<api-ref title="get specific setting  " verb="get" route="/api/general-settings/{setting}" :response-codes="[200]">
    <template v-slot:description>
    جلب كل أعداد  خاص 
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'setting' => {object},
}
</pre>
</template>
</api-ref>

##تحديث الأعدادات

<api-ref title="update the general settings" verb="post" route="/api/general-settings" :response-codes="[200]">
    <template v-slot:description>
        تحديث كل الأعدادات العامة
    </template>
    <template v-slot:body>
        <api-ref-item name="settings" :required="true" type="array">
            an array which the key the key of the setting and the value is it's value
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'general settings updated successfully',
}
</pre>
</template>
</api-ref>

##جلب بيانات اتصل بنا

<api-ref title="get all contact info " verb="get" route="/api/contact-info/" :response-codes="[200]">
    <template v-slot:description>
    email,phone,facebook,instagram,twitter
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'data' => {array},
}
</pre>
</template>
</api-ref>

##جلب بيانات اصدارات التطبيقات

<api-ref title="get all app versions info " verb="get" route="/api/mobile-versions/" :response-codes="[200]">
    <template v-slot:description>
        all apps versions in array
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'data' => {array},
}
</pre>
</template>
</api-ref>
