## قائمة السلايدر

<api-ref title="get all sliders" verb="get" route="/api/sliders" :response-codes="[200]">
    <template v-slot:description>
    جلب كل السلايدرس من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'sliders' => [array], }
</pre>
</template>
</api-ref>

## اضافة صنف

<api-ref title="add new slider" verb="post" route="/api/sliders" :response-codes="[200]">
    <template v-slot:description>
اضافة صنف جديد
    </template>
    <template v-slot:body>
      <api-ref-item name="title" :required="true" type="string">
       the slider title  (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
      <api-ref-item name="content" :required="true" type="string">
       the slider content  (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
      <api-ref-item name="image" :required="true" type="image">
       the slider image  (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'slider created successfully',
}
</pre>
</template>
</api-ref>

## عرض السليدر

<api-ref title="get slider data" verb="get" route="/api/sliders/{slider}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات السليدر من  قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'slider' =>{slider},
}
</pre>
</template>
</api-ref>

## تعديل سليدر

<api-ref title=" update slider" verb="patch" route="/api/sliders/{slider}" :response-codes="[200]">
    <template v-slot:description>
تعديل بيانات صنف 
    </template>
    <template v-slot:body>
      <api-ref-item name="title" :required="false" type="string">
       the slider title  (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
      <api-ref-item name="content" :required="false" type="string">
       the slider content  (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
      <api-ref-item name="image" :required="false" type="image">
       the slider image  (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'slider updated successfully',
}
</pre>
</template>
</api-ref>

### حذف السليدر

<api-ref title=" delete  a slider" verb="delete" route="/api/sliders/{slider}" :response-codes="[200]">
    <template v-slot:description>
  حذف السليدر   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  slider  deleted successfully",
}
        </pre>
    </template>
</api-ref>
