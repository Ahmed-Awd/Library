# ارسل رأيك

## مقدمة

بمكان أصافة سائق جديدة

### عرض بيانات كل الاراء

<api-ref title="get all  feedbacks" verb="Get" route="/api/feedback" :response-codes="[200]">
    <template v-slot:description>
جلب كل الآراء    
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "feedbacks":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  رأى جديد

<api-ref title="create new feedback" verb="post" route="api/feedback/send" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  الرأى  
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            The sender name 
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="string">
            The sender email 
        </api-ref-item>
        <api-ref-item name="phone" :required="true" type="string">
            The sender phone full 
        </api-ref-item>
        <api-ref-item name="message" :required="true" type="string">
            The message 
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

### عرض بيانات الرأى

<api-ref title="get feedback data" verb="Get" route="/api/feedback/{feedback}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات الرأى   
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

###  حذف الرأى

<api-ref title=" delete driver data" verb="delete" route="/api/feedback/{feedback}" :response-codes="[200]">
    <template v-slot:description>
  حذف الرأى   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  feedback  deleted successfully",
}
        </pre>
    </template>
</api-ref>

###الرد على رأى
