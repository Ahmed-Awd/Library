# المشرف(admin)

## مقدمة

بمكان أصافة مشرف ومنحه صلاحيات خاصة للكل مشرف

### عرض بيانات كل المشرفين

<api-ref title="get all  admin data" verb="Get" route="/api/admin/user" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات المشرفين   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "admins":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  مشرف  جديد

<api-ref title="create new admin" verb="post" route="/api/admin/user" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  المشرف  
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            The admin name 
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="string">
            The admin email 
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The admin password 
        </api-ref-item>
        <api-ref-item name="password_confirmation" :required="true" type="string">
            The admin password again
        </api-ref-item>
        <api-ref-item name="city_id" :required="true" type="string">
            The admin city 
        </api-ref-item>
        <api-ref-item name="phone" :required="true" type="string">
            The admin phone 
        </api-ref-item>
        <api-ref-item name="country_code" :required="true" type="string">
            The admin country code 
        </api-ref-item>
         <api-ref-item name="is_disabled" :required="false" type="string">
            The admin is disabled
        </api-ref-item>
        <api-ref-item name="profile_photo_path" :required="false" type="file">
        The admin profile photo 
        </api-ref-item>
         <api-ref-item name="permissions" :required="false" type="array">
         The permissions is array of name permissions
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
    "message": "A new admin  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات المشرف

<api-ref title="get all  admin data" verb="Get" route="/api/admin/user/{user}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات المشرف   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "data":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  بيانات المشرف

<api-ref title="update  admin data" verb="put" route="/api/admin/user/{user}" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  المشرف  
    </template>
     <template v-slot:body>
   <api-ref-item name="name" :required="true" type="string">
            The admin name 
        </api-ref-item>
        <api-ref-item name="city_id" :required="false" type="string">
            The admin city 
        </api-ref-item>
        <api-ref-item name="phone" :required="false" type="string">
            The admin phone 
        </api-ref-item>
        <api-ref-item name="country_code" :required="false" type="string">
            The admin country code 
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
    "message": "A admin  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف المشرف

<api-ref title=" delete admin data" verb="delete" route="/api/admin/user/{user}" :response-codes="[200]">
    <template v-slot:description>
  حذف المشرف   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  admin  deleted successfully",
}
        </pre>
    </template>
</api-ref>

### ربط المشرف بصلاحية  


<api-ref title="add new permission" verb="post" route="/api/admin/user/add-permission/{user}" :response-codes="[200]">
    <template v-slot:description>
ربط المشرف بصلاحية
    </template>
    <template v-slot:body>
    <api-ref-item name="permission" :required="true" type="string">
            the permission name
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
    "message": "permission attached successfully ",
}
        </pre>
    </template>
</api-ref>


### الغاء ربط المشرف بصلاحية 


<api-ref title="delete exist permission" verb="post" route="/api/admin/user/remove-permission/{user}" :response-codes="[200]">
    <template v-slot:description>
الغاء ربط المشرف بصلاحية 
    </template>
    <template v-slot:body>
        <api-ref-item name="permission" :required="true" type="string">
            the permission name
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
    "message": "permission detached successfully ",
}
        </pre>
    </template>
</api-ref>
