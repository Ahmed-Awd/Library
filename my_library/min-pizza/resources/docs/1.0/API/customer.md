# العميل

## مقدمة

بمكان أصافة عميل جديد

### عرض بيانات كل العميل

<api-ref title="get all  customers data" verb="Get" route="/api/customers" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات العميل   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "customers":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  عميل جديد

<api-ref title="create new customer" verb="post" route="/api/customers" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  العميل  
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            The customer name 
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="string">
            The customer email 
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The customer password 
        </api-ref-item>
        <api-ref-item name="password_confirmation" :required="true" type="string">
            The customer password again
        </api-ref-item>
        <api-ref-item name="city_id" :required="true" type="string">
            The customer city 
        </api-ref-item>
        <api-ref-item name="phone" :required="true" type="string">
            The customer phone 
        </api-ref-item>
        <api-ref-item name="country_code" :required="true" type="string">
            The customer country code 
        </api-ref-item>
        
        
        
         <api-ref-item name="is_disabled" :required="false" type="string">
                            The customer is disabled
                        </api-ref-item>
               
               
                <api-ref-item name="profile_photo_path" :required="false" type="string">
                           The customer profile photo 
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
    "message": "A new customer  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات العميل

<api-ref title="get all  customer data" verb="Get" route="/api/customers/{customer}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات العميل   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "customer":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات العميل

<api-ref title="update  customer data" verb="put" route="/api/customers/{customer}" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  العميل  
    </template>
     <template v-slot:body>
   <api-ref-item name="name" :required="true" type="string">
            The customer name 
        </api-ref-item>
        <api-ref-item name="city_id" :required="false" type="string">
            The customer city 
        </api-ref-item>
        <api-ref-item name="phone" :required="false" type="string">
            The customer phone 
        </api-ref-item>
        <api-ref-item name="country_code" :required="false" type="string">
            The customer country code 
        </api-ref-item>
        
         <api-ref-item name="is_disabled" :required="false" type="string">
                    The customer is disabled
                </api-ref-item>
       
       
        <api-ref-item name="profile_photo_path" :required="false" type="string">
                   The customer profile photo 
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
    "message": "A customer  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف العميل

<api-ref title=" delete customer data" verb="delete" route="/api/customers/{customer}" :response-codes="[200]">
    <template v-slot:description>
  حذف العميل   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  customer  deleted successfully",
}
        </pre>
    </template>
</api-ref>

### تسجيل حساب جديد كزبون

<api-ref title="register as customer" verb="post" route="/api/register" :response-codes="[200]">
    <template v-slot:description>
تسجيل حساب جديد كمستخدم من نوع زبون و يحتاج الى email verify
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            name 
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="email">
            The user email 
        </api-ref-item>
        <api-ref-item name="name" :required="true" type="string">
            password 
        </api-ref-item>
        <api-ref-item name="name" :required="true" type="string">
            password_confirmation 
        </api-ref-item>
         <api-ref-item name="phone" :required="true" type="integer">
            The user phone 
        </api-ref-item>
         <api-ref-item name="country_code" :required="true" type="integer">
            The user country code 
        </api-ref-item>
        <api-ref-item name="city_id" :required="true" type="integer">
           id of the city
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "Email verification link sent on your email address",
}
        </pre>
    </template>
</api-ref>
