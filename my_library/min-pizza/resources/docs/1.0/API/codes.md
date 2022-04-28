# أكواد الخصم


### عرض بيانات كل الأكواد

<api-ref title="get all  discount codes" verb="Get" route="/api/discount-codes" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الأكواد   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "discount_codes":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  كود الجديد

<api-ref title="create new code" verb="post" route="/api/discount-codes" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  الكود الجديدة 
    </template>
    <template v-slot:body>
        <api-ref-item name="type" :required="true" type="string(enum)">
            (rate,fixed)
        </api-ref-item>
        <api-ref-item name="user_id" :required="true" type="numeric">
            the user id 
        </api-ref-item>
        <api-ref-item name="restaurant_id" :required="true" type="numeric">
            the restaurant id or null in case of all restaurants
        </api-ref-item>
        <api-ref-item name="amount" :required="true" type="numeric">
            the amount of discount (fixed amount or rate)
        </api-ref-item>
        <api-ref-item name="max_usage" :required="true" type="numeric">
            the max number that this code can be used
        </api-ref-item>
        <api-ref-item name="min_order_price" :required="true" type="numeric">
             the min order price required for this discount code 
        </api-ref-item>       
        <api-ref-item name="start_date" :required="true" type="date">
             the start date of the code
        </api-ref-item>        
        <api-ref-item name="end_date" :required="true" type="date">
             the expire date of the code
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
    "message": "A new code  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات الكود

<api-ref title="get code data" verb="Get" route="/api/discount-codes/{code}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات الكود   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "code":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات الكود

<api-ref title="update  code data" verb="patch" route="/api/discount-codes/{code}" :response-codes="[200]">
    <template v-slot:description>
 تعديل بيانات  الكود  
    </template>
     <template v-slot:body>
        <api-ref-item name="restaurant_id" :required="false" type="numeric">
            the restaurant id or null in case of all restaurants
        </api-ref-item>
        <api-ref-item name="min_order_price" :required="false" type="numeric">
             the min order price required for this discount code 
        </api-ref-item>
        <api-ref-item name="start_date" :required="false" type="date">
             the start date of the code
        </api-ref-item>        
        <api-ref-item name="end_date" :required="false" type="date">
             the expire date of the code
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
    "message": "A code  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف الكود

<api-ref title=" delete code data" verb="delete" route="/api/discount-codes/{code}" :response-codes="[200]">
    <template v-slot:description>
  حذف الكود   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  code  deleted successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات كل الأكواد الخاصة بى

<api-ref title="get my  discount codes" verb="Get" route="/api/my/discount-codes" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الأكواد   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "discount_codes":[array],
}
        </pre>
    </template>
</api-ref>
