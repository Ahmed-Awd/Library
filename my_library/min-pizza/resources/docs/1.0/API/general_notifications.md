#ارسال اشعار

## مقدمة

يكمن للادمن ارسال اشعارات جديدة

### عرض الاشعرات

<api-ref title="get all  notifications" verb="Get" route="/api/get/notification/{type}" :response-codes="[200]">
    <template v-slot:description>
جلب كل الاشعارات عن طريق النوع    
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
        <api-ref-item name="type" :required="true" type="string" example="application/json">
            type (general,special)
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "notifications":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  أشعار جديد عام

<api-ref title="create new general notification" verb="post" route="api/send/notification/general" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  الأشعار  
    </template>
    <template v-slot:body>
        <api-ref-item name="subject" :required="true" type="string">
            The subject of the notification 
        </api-ref-item>
        <api-ref-item name="body" :required="true" type="string">
            The body of the message 
        </api-ref-item>
        <api-ref-item name="role" :required="true" type=" id of rile">
            the role id 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "your message has been sent",
}
        </pre>
    </template>
</api-ref>

##   اضافة  أشعار جديد خاص

<api-ref title="create new general notification" verb="post" route="api/send/notification/special" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  الأشعار  
    </template>
    <template v-slot:body>
        <api-ref-item name="subject" :required="true" type="string">
            The subject of the notification 
        </api-ref-item>
        <api-ref-item name="body" :required="true" type="string">
            The body of the message 
        </api-ref-item>
        <api-ref-item name="users" :required="true" type="array of numbers">
            array of user ids 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "your message has been sent",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات الأشعار

<api-ref title="get notification data" verb="Get" route="/api/show/notification/{notification}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات الأشعار   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "record":{},
}
        </pre>
    </template>
</api-ref>
