# قائمة المطعم


##قائمة طعام المطعم كله

<api-ref title="get all  menu of restaurant" verb="Get" route="/api/menu/{restaurant}" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات قائمة المطعم   
 </template>
    <template v-slot:200>
        <pre>
{
    "menu":[array],
}
        </pre>
    </template>
</api-ref>


##أصناف القائمة


### عرض بيانات كل الأصناف

<api-ref title="get all  categories data" verb="Get" route="/api/menu/restaurant/categories/{restaurant}" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الأصناف   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "categories":[array],
}
        </pre>
    </template>
</api-ref>

###   أصافة  صنف جديد

<api-ref title="create new category" verb="post" route="/api/menu/category/{restaurant}" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  الصنف الجديد 
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            the category name (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
        <api-ref-item name="description" :required="true" type="text">
             the description of category (in the origin language (no translation will be applied))
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
    "message": "A new category  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات الصنف

<api-ref title="get category data" verb="Get" route="/api/menu/category/{category}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات الصنف   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "category":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات الصنف

<api-ref title="update  category data" verb="patch" route="/api/menu/category/{category}" :response-codes="[200]">
    <template v-slot:description>
 تعديل بيانات  الصنف  
    </template>
     <template v-slot:body>
        <api-ref-item name="name" :required="false" type="string">
            the category name (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
        <api-ref-item name="description" :required="false" type="text">
             the description of category (in the origin language (no translation will be applied))
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
    "message": "A category  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف الصنف

<api-ref title=" delete category data" verb="delete" route="/api/menu/category/{category}" :response-codes="[200]">
    <template v-slot:description>
  حذف الصنف   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  category  deleted successfully",
}
        </pre>
    </template>
</api-ref>

