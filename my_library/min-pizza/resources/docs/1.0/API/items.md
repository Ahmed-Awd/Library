
##أغراض القائمة


### عرض بيانات كل الأغراض

<api-ref title="get all  items data" verb="Get" route="/api/menu/category/items/{category}" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الأغراض   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "items":[array],
}
        </pre>
    </template>
</api-ref>


###   اضافة  غرض جديد

<api-ref title="create new item" verb="post" route="/api/menu/item/{category}" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  الغرض الجديد 
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            the item name (in the origin language (no translation will be applied))
        </api-ref-item>
        <api-ref-item name="description" :required="false" type="text">
             the description of item (in the origin language (no translation will be applied))
        </api-ref-item>
        <api-ref-item name="image" :required="false" type="image">
             the image of the item
        </api-ref-item>
        <api-ref-item name="alcohol_percentage" :required="true" type="number">
             the alcohol percentage
        </api-ref-item>
        <api-ref-item name="tax_id" :required="false" type="number">
             the tax id of the item
        </api-ref-item>
        <api-ref-item name="option_template_id" :required="false" type="number">
             the option template id of the option_template
        </api-ref-item>
         <api-ref-item name="option_id" :required="false" type="array">
             the option id is array content id of  the option_categories
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
    "message": "A new item  created successfully",
}
        </pre>
    </template>
</api-ref>



### تعديل  بيانات الغرض

<api-ref title="update  item data" verb="patch" route="/api/menu/item/{item}" :response-codes="[200]">
    <template v-slot:description>
 تعديل   نفس البيانات في التخزين ماعدا option_id  
    </template>
     <template v-slot:body>
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A item  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف الغرض

<api-ref title=" delete item data" verb="delete" route="/api/menu/item/{item}" :response-codes="[200]">
    <template v-slot:description>
  حذف الغرض   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  item  deleted successfully",
}
        </pre>
    </template>
</api-ref>


###ربط غرض بخيار 


<api-ref title="add new option" verb="post" route="/api/menu/item/add-option/{item}" :response-codes="[200]">
    <template v-slot:description>
 ربط   الغرض بخيار 
    </template>
    <template v-slot:body>
        <api-ref-item name="option_id" :required="true" type="number">
             the option category id 
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
    "message": "item attached successfully ",
}
        </pre>
    </template>
</api-ref>


###الغاء ربط غرض بخيار


<api-ref title="delete exist option" verb="post" route="/api/menu/item/remove-option/{item}" :response-codes="[200]">
    <template v-slot:description>
 ألغاء ربط   الغرض بخيار 
    </template>
    <template v-slot:body>
        <api-ref-item name="option_id" :required="true" type="number">
             the option category id 
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
    "message": "item detached successfully ",
}
        </pre>
    </template>
</api-ref>


###تغيير حالة الأتاحة

<api-ref title="delete exist option" verb="post" route="/api/menu/item/change-availability/{item}" :response-codes="[200]">
    <template v-slot:description>
 تغيير الحالة من العنصر متاح الى غير متاح او العكس 
    </template>
    <template v-slot:body>
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
    "status": "boolean",
}
        </pre>
    </template>
</api-ref>
