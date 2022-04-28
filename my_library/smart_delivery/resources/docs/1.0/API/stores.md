# المطاعم التابعة للشركات الخارجية

## مقدمة

بمكان صاحب الشركة اضافة مطاعم جديدة 

### عرض بيانات كل المطاعم التي مع الشركة

<api-ref title="get all  store data" verb="Get" route="/api/company/stores" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات المطاعم    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "stores":stores,
}
        </pre>
    </template>
</api-ref>

### جلب الانشطة المتاحة 
جلب كل الانشطة المتاحة من اجل استخدمه في تحديد نوع النشاط عند اضافة مطعم جديد
<api-ref title="get all activity type data" verb="Get" route="/api/types" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الانشطة     </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "types":types,
}
        </pre>
    </template>
</api-ref>


##   اضافة  مطعم الجديد 

<api-ref title="create new store" verb="post" route="/api/company/stores" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  المطعم جديدة 
    </template>
    <template v-slot:body>
        <api-ref-item name="store_name" :required="true" type="string">
            The store name 
        </api-ref-item>
        <api-ref-item name="location" :required="true" type="array">
            The array contain lat and lng 
        </api-ref-item>
        <api-ref-item name="address" :required="true" type="string">
            The store address 
        </api-ref-item>
        <api-ref-item name="activity_type" :required="true" type="number">
             the acticity type  
        </api-ref-item>
    <api-ref-item name="owner_name" :required="true" type="string">
          the owner name 
        </api-ref-item>
        <api-ref-item name="owner_username" :required="true" type="string">
          the owner username 
        </api-ref-item>
        <api-ref-item name="owner_email" :required="true" type="string">
          the owner email 
        </api-ref-item>
    <api-ref-item name="default_delivery_time" :required="true" type="number">
            The default delivery time
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
    "message": "A new store  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات المطعم  

<api-ref title="get all  store data" verb="Get" route="/api/company/stores/{store}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات المطعم   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "store":store,
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات المطعم

<api-ref title="update  store data" verb="put" route="/api/company/stores/{store}" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  المطعم  
    </template>
    <template v-slot:body>
        <api-ref-item name="store_name" :required="true" type="string">
            The store name 
        </api-ref-item>
        <api-ref-item name="location" :required="true" type="array">
            The array contain lat and lng 
        </api-ref-item>
        <api-ref-item name="address" :required="true" type="string">
            The store address 
        </api-ref-item>
        <api-ref-item name="activity_type" :required="true" type="number">
             the acticity type  
        </api-ref-item>
    <api-ref-item name="owner_name" :required="true" type="string">
          the owner name 
        </api-ref-item>
        <api-ref-item name="owner_username" :required="true" type="string">
          the owner username 
        </api-ref-item>
        <api-ref-item name="owner_email" :required="true" type="string">
          the owner email 
        </api-ref-item>
    <api-ref-item name="default_delivery_time" :required="true" type="number">
            The default delivery time
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
    "message": "A  store  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف المطعم  

<api-ref title="delete  store " verb="delete" route="/api/company/stores/{store}" :response-codes="[200]">
    <template v-slot:description>
  حذف المطعم   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  store  deleted successfully",
}
        </pre>
    </template>
</api-ref>

###  تعديل اعدادات المطعم

<api-ref title="update  store settings" verb="post" route="/api/stores/setting" :response-codes="[200]">
    <template v-slot:description>
 تحديث اعدادات  المطعم  
    </template>
    <template v-slot:body>
        <api-ref-item name="delivery_perice_percentage" :required="true" type="numeric">
            percentage from 0 to 100 that the store will pay from the delivery price 
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
    "message": "store settings updated",
}
        </pre>
    </template>
</api-ref>

### عرض اصناف المطعم

<api-ref title="get all  store types" verb="Get" route="/api/store-types" :response-codes="[200]">
    <template v-slot:description>
جلب  اصناف المطعم   
 </template>
    <template v-slot:200>
        <pre>
{
    "types":array,
}
        </pre>
    </template>
</api-ref>
