# السائق

## مقدمة

بمكان أصافة سائق جديدة

### عرض بيانات كل السائق

<api-ref title="get all  drivers data" verb="Get" route="/api/drivers" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات السائق   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "drivers":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  سائق الجديد

<api-ref title="create new driver" verb="post" route="/api/drivers" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  السائق  
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            The driver name 
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="string">
            The driver email 
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The driver password 
        </api-ref-item>
        <api-ref-item name="password_confirmation" :required="true" type="string">
            The driver password again
        </api-ref-item>
        <api-ref-item name="city_id" :required="true" type="string">
            The driver city 
        </api-ref-item>
        <api-ref-item name="phone" :required="true" type="string">
            The driver phone 
        </api-ref-item>
        <api-ref-item name="country_code" :required="true" type="string">
            The driver country code 
        </api-ref-item>
        <api-ref-item name="type" :required="true" type="enum(app,restaurant)">
          the driver name
        </api-ref-item>
        <api-ref-item name="restaurant_id" :required="false" type="number">
          the restaurant id if it belongs to one 
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
    "message": "A new driver  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات السائق

<api-ref title="get all  driver data" verb="Get" route="/api/drivers/{driver}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات السائق   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "driver":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات السائق

<api-ref title="update  driver data" verb="put" route="/api/drivers/{driver}" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  السائق  
    </template>
     <template v-slot:body>
   <api-ref-item name="name" :required="true" type="string">
            The driver name 
        </api-ref-item>
        <api-ref-item name="city_id" :required="false" type="string">
            The driver city 
        </api-ref-item>
        <api-ref-item name="phone" :required="false" type="string">
            The driver phone 
        </api-ref-item>
        <api-ref-item name="country_code" :required="false" type="string">
            The driver country code 
        </api-ref-item>
        <api-ref-item name="type" :required="false" type="enum(app,restaurant)">
          the driver name
        </api-ref-item>
        <api-ref-item name="restaurant_id" :required="false" type="number">
          the restaurant id if it belongs to one 
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
    "message": "A driver  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف السائق

<api-ref title=" delete driver data" verb="delete" route="/api/drivers/{driver}" :response-codes="[200]">
    <template v-slot:description>
  حذف السائق   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  driver  deleted successfully",
}
        </pre>
    </template>
</api-ref>


###تغير الحالة للسائق

<api-ref title="driver change status" verb="post" route="/api/driver/change-status" :response-codes="[200]">
    <template v-slot:description>
  تغيير حالة السائق ليقبل الطلبات   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "status changed to active / inactive",
}
        </pre>
    </template>
</api-ref>
