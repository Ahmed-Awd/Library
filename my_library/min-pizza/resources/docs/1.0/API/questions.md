# الأسئلة


### عرض بيانات كل الأسئلة

<api-ref title="get all  FQA data" verb="Get" route="api/FQA" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الأسئلة   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "questions":[array],
}
        </pre>
    </template>
</api-ref>

##   أضافه  سؤال الجديد

<api-ref title="create new question" verb="post" route="api/FQA" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  السؤال الجديدة 
    </template>
    <template v-slot:body>
        <api-ref-item name="question" :required="true" type="string">
            the question in english (must be english for translations) 
        </api-ref-item>
        <api-ref-item name="answer" :required="true" type="string">
             the answer in english (must be english for translations) 
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
    "message": "A new question  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات السؤال

<api-ref title="get question data" verb="Get" route="api/FQA/{FQA}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات السؤال   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "question":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات السؤال

<api-ref title="update  question data" verb="patch" route="api/FQA/{FQA}" :response-codes="[200]">
    <template v-slot:description>
 تعديل بيانات  السؤال  
    </template>
     <template v-slot:body>
        <api-ref-item name="question" :required="true" type="string">
            the question in english (must be english for translations) 
        </api-ref-item>
        <api-ref-item name="answer" :required="true" type="string">
             the answer in english (must be english for translations) 
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
    "message": "A question  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف السؤال

<api-ref title=" delete question" verb="delete" route="api/FQA/{FQA}" :response-codes="[200]">
    <template v-slot:description>
  حذف السؤال   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  question  deleted successfully",
}
        </pre>
    </template>
</api-ref>
