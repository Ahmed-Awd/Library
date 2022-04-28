## قائمة أسباب الرفض للطلب

<api-ref title="get all reasons" verb="get" route="/api/reasons" :response-codes="[200]">
    <template v-slot:description>
    جلب كل الاسباب من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'reasons' => [array], }
</pre>
</template>
</api-ref>

## اضافة صنف

<api-ref title="add new reason" verb="post" route="/api/reasons" :response-codes="[200]">
    <template v-slot:description>
اضافة صنف جديد
    </template>
    <template v-slot:body>
      <api-ref-item name="reason" :required="true" type="string">
       the reason  (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'reason created successfully',
}
</pre>
</template>
</api-ref>

## عرض السبب

<api-ref title="get reason data" verb="get" route="/api/reasons/{reason}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات السبب من  قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'reason' =>{reason},
}
</pre>
</template>
</api-ref>

## تعديل صنف

<api-ref title=" update reason" verb="patch" route="/api/reasons/{reason}" :response-codes="[200]">
    <template v-slot:description>
تعديل بيانات صنف 
    </template>
    <template v-slot:body>
      <api-ref-item name="reason" :required="true" type="string">
            the reason (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'reason updated successfully',
}
</pre>
</template>
</api-ref>

### حذف السبب

<api-ref title=" delete  a reason" verb="delete" route="/api/reasons/{reason}" :response-codes="[200]">
    <template v-slot:description>
  حذف السبب   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  reason  deleted successfully",
}
        </pre>
    </template>
</api-ref>
