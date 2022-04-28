## قائمة عنواين العملاء

<api-ref title="get all addresses of the current logged user" verb="get" route="/api/addresses" :response-codes="[200]">
    <template v-slot:description>
    جلب كل العنواين المستخدم الحالى من قاعدة البيانات من قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>
{
        'addresses' => [array],
}
        </pre>
    </template>
</api-ref>

##  اضافة عنوان 

<api-ref title="add new address" verb="post" route="/api/addresses" :response-codes="[200]">
    <template v-slot:description>
اضافة عنوان جديد
    </template>
    <template v-slot:body>
        <api-ref-item name="title" :required="true" type="string">
        </api-ref-item>
        <api-ref-item name="lat" :required="true" type="decimal">
        </api-ref-item>
        <api-ref-item name="lng" :required="true" type="decimal">
        </api-ref-item>
        <api-ref-item name="area" :required="false" type="string">
        </api-ref-item>
        <api-ref-item name="description" :required="false" type="string">
        </api-ref-item>
        <api-ref-item name="ZIP_code" :required="false" type="number">
        </api-ref-item>
        <api-ref-item name="Apartment" :required="false" type="string">
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'address created successfully',
}
</pre>
</template>
</api-ref>

##  عرض العنوان 

<api-ref title="get address data" verb="get" route="/api/addresses/{address}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات العنوان من  قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
        'addresses' => [array],
}
</pre>
</template>
</api-ref>

##  عرض عناوين مستخدم

<api-ref title="get address data" verb="get" route="/api/addresses/of/{user}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات عناوين مستخدم معين
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'address' =>{address},
}
</pre>
</template>
</api-ref>

##  تعديل عنوان 

<api-ref title=" update address" verb="put" route="/api/addresses/{address}" :response-codes="[200]">
    <template v-slot:description>
تعديل بيانات عنوان 
    </template>
       <template v-slot:body>
        <api-ref-item name="title" :required="false" type="string">
        </api-ref-item>
        <api-ref-item name="lat" :required="false" type="decimal">
        </api-ref-item>
         <api-ref-item name="lng" :required="false" type="decimal">
        </api-ref-item>
        <api-ref-item name="user_id" :required="false" type="int">
        </api-ref-item>
        <api-ref-item name="area" :required="false" type="string">
        </api-ref-item>
        <api-ref-item name="description" :required="false" type="string">
        </api-ref-item>
         <api-ref-item name="ZIP_code" :required="false" type="number">
        </api-ref-item>
        <api-ref-item name="Apartment" :required="false" type="string">
        </api-ref-item>
       </template>
</api-ref>

###  حذف العنوان  

<api-ref title=" delete address data" verb="delete" route="/api/addresses/{address}" :response-codes="[200]">
    <template v-slot:description>
  حذف العنوان   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  address  deleted successfully",
}
        </pre>
    </template>
</api-ref>
