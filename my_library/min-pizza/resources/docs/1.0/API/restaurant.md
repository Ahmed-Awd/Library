# المطاعم

### عرض بيانات كل المطاعم   

<api-ref title="get all  restaurants data" verb="Get" route="/api/restaurants" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات المطاعم   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "restaurants":[array],
}
        </pre>
    </template>
</api-ref>

###   اضافة  مطعم الجديد 

<api-ref title="create new restaurant" verb="post" route="/api/restaurants" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  المطعم جديدة 
    </template>
    <template v-slot:body>
        <api-ref-item name="owner_name" :required="true" type="string">
            The owner name 
        </api-ref-item>
        <api-ref-item name="owner_email" :required="true" type="string">
            The owner email 
        </api-ref-item>
        <api-ref-item name="city_id" :required="true" type="number">
            The owner city 
        </api-ref-item>
        <api-ref-item name="phone" :required="true" type="number">
            The owner phone 
        </api-ref-item>
        <api-ref-item name="country_code" :required="true" type="number">
            The owner country code 
        </api-ref-item>
        <api-ref-item name="restaurant_name" :required="true" type="string">
          the restaurant name
        </api-ref-item>
        <api-ref-item name="location" :required="true" type="array">
            The array contain lat and lng 
        </api-ref-item>
        <api-ref-item name="categories" :required="true" type="array">
            The array categories contain id of category
        </api-ref-item>
        <api-ref-item name="phones" :required="true" type="array">
            The array phones contain phone and country_code
        </api-ref-item>
        <api-ref-item name="address" :required="true" type="string">
            The restaurant address 
        </api-ref-item>
        <api-ref-item name="min_order_price" :required="true" type="number">
             the min order price  
        </api-ref-item>
         <api-ref-item name="delivery_type" :required="true" type="string">
             the delivery type must be is percent , value or free or  per_kilometer
        </api-ref-item>
         <api-ref-item name="delivery_value" :required="true" type="number">
             the delivery value 
        </api-ref-item>
        <api-ref-item name="delivery_kilometer" :required="true" type="number">
             the delivery kilometer 
        </api-ref-item>
        <api-ref-item name="logo" :required="false" type="image">
             the logo of the restaurant
        </api-ref-item>
        <api-ref-item name="image" :required="false" type="image">
             the image of the restaurant
        </api-ref-item>
        <api-ref-item name="company_name" :required="true" type="string">
          the  company name 
        </api-ref-item>
        <api-ref-item name="company_number" :required="true" type="number">
          the company number 
        </api-ref-item>
        <api-ref-item name="ZIP_code" :required="true" type="number">
                The ZIP code
            </api-ref-item>
        <api-ref-item name="status_id" :required="true" type="number">
                The status
        </api-ref-item>
         <api-ref-item name="scheduling_order" :required="false" type="boolean">
             restaurant is support scheduling order
        </api-ref-item>
        <api-ref-item name="vat" :required="true" type="string">
                The ZIP code
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
    "message": "A new restaurant  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات المطعم  

<api-ref title="get all  restaurant data" verb="Get" route="/api/restaurants/{restaurant}" :response-codes="[200]">
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
    "restaurant":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات المطعم

<api-ref title="update  restaurant data" verb="put" route="/api/restaurants/{restaurant}" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  المطعم  
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
    "message": "A restaurant  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف المطعم  

<api-ref title=" delete restaurant data" verb="delete" route="/api/restaurants/{restaurant}" :response-codes="[200]">
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
    "message": "A  restaurant  deleted successfully",
}
        </pre>
    </template>
</api-ref>
