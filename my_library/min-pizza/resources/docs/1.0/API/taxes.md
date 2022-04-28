# الضرائب


### عرض بيانات كل الضرائب

<api-ref title="get all  taxes data" verb="Get" route="/api/taxes" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الضرائب   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "taxes":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  ضريبة الجديد

<api-ref title="create new tax" verb="post" route="/api/taxes" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  الضريبة الجديدة 
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            the tax name 
        </api-ref-item>
        <api-ref-item name="percentage" :required="true" type="number">
             the percentage of tax 
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
    "message": "A new tax  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات الضريبة

<api-ref title="get tax data" verb="Get" route="/api/taxes/{tax}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات الضريبة   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "tax":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات الضريبة

<api-ref title="update  tax data" verb="patch" route="/api/taxes/{tax}" :response-codes="[200]">
    <template v-slot:description>
 تعديل بيانات  الضريبة  
    </template>
     <template v-slot:body>
        <api-ref-item name="name" :required="false" type="string">
            the tax name 
        </api-ref-item>
        <api-ref-item name="percentage" :required="false" type="number">
             the percentage of tax 
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
    "message": "A tax  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف الضريبة

<api-ref title=" delete tax data" verb="delete" route="/api/taxes/{tax}" :response-codes="[200]">
    <template v-slot:description>
  حذف الضريبة   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  tax  deleted successfully",
}
        </pre>
    </template>
</api-ref>
