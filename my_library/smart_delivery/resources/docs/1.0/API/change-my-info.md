#تغيير بياناتى

##تغيير بياناتى كصاحب مطعم
<api-ref title="edit my info as owner" verb="post" route="/api/owner/update/my-info" :response-codes="[200]">
    <template v-slot:description>
        تغيير بياناتى كصاحب مطعم 
    </template>
    <template v-slot:body>
        <api-ref-item name="store_name" :required="true" type="string">
            The store name 
        </api-ref-item>
        <api-ref-item name="owner_name" :required="true" type="string">
            The owner name 
        </api-ref-item>
        <api-ref-item name="town_id" :required="true" type="numeric">
            The town id 
        </api-ref-item>
        <api-ref-item name="address" :required="true" type="string">
            The store address 
        </api-ref-item>
         <api-ref-item name="type_id" :required="true" type="integer">
            type of activity from type table
        </api-ref-item>
        <api-ref-item name="lat" :required="true" type="numeric">
            The lat of the restaurant
        </api-ref-item>
        <api-ref-item name="lng" :required="true" type="numeric">
            The lng of the restaurant
        </api-ref-item>
        <api-ref-item name="default_delivery_time" :required="true" type="numeric">
            the default delivery time
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
"message": "operation done successfully",
}
</pre>
</template>
</api-ref>


##تغيير بياناتى الشخصية كسائق
<api-ref title="edit my personal info as driver" verb="post" route="/api/driver/update/my-info" :response-codes="[200]">
    <template v-slot:description>
        تغيير بياناتى الشخصية كسائق 
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="false" type="string">
            my name 
        </api-ref-item>
        <api-ref-item name="town_id" :required="false" type="numeric">
            The town id 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
"message": "operation done successfully",
}
</pre>
</template>
</api-ref>

##تغيير رقم الهاتف
<api-ref title="edit my phone number" verb="post" route="/api/driver/update/my-phone" :response-codes="[200]">
    <template v-slot:description>
        تغيير رقم الهاتف
    </template>
    <template v-slot:body>
         <api-ref-item name="country_code" :required="false" type="numeric">
            The user country code 
        </api-ref-item>
         <api-ref-item name="phone" :required="false" type="numeric">
            The user phone 
        </api-ref-item>  
    </template>
    <template v-slot:200>
        <pre>
{
"message": "please verify your new phone number , a code has been sent to you via message",
}
</pre>
</template>
</api-ref>

##اعادة ارسال كود تغيير رقم الهاتف
<api-ref title="resend code again" verb="post" route="/api/driver/resend/code/new-phone" :response-codes="[200]">
    <template v-slot:description>
        اعادة ارسال كود تغيير رقم الهاتف
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
"message": "please verify your new phone number , a code has been sent to you via message",
}
</pre>
</template>
</api-ref>


##تغيير بياناتى الورقية كسائق


<api-ref title="edit my license info as driver" verb="post" route="/api/driver/update/my-papers" :response-codes="[200]">
    <template v-slot:description>
        تغيير بياناتى الورقية كسائق 
    </template>
    <template v-slot:body>
        <api-ref-item name="personal_photo" :required="false" type="string">
            my personal photo path
        </api-ref-item>
        <api-ref-item name="license_photo" :required="false" type="string">
            my license photo path
        </api-ref-item>
        <api-ref-item name="vehicle_photo" :required="false" type="string">
            my vehicle photo path
        </api-ref-item>
        <api-ref-item name="vehicle_license_photo" :required="false" type="string">
            my vehicle license photo  path
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
"message": "operation done successfully",
"message2": "please wait for admin confirmation",
}
</pre>
</template>
</api-ref>


## تغيير كلمة المرور

<api-ref title="change password" verb="post" route="/api/password/change" :response-codes="[200,403]">
    <template v-slot:description>
يمكن لكل مستخدم تغيير كلمة المرور الخاصة به
    </template>
    <template v-slot:body>
         <api-ref-item name="old_password" :required="true" type="string">
            your old password
        </api-ref-item>
         <api-ref-item name="password" :required="true" type="string">
            your new password
        </api-ref-item>
         <api-ref-item name="password_confirmation" :required="true" type="string">
            your new password repeated for confirmation
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' => 'password changed successfully',
}
</pre>
</template>
<template v-slot:403>
<pre>
{
"message": "invalid old password"
}
</pre>
</template>
</api-ref>

##تأكيد الرقم الجديد كسائق

<api-ref title="confirm my new phone" verb="post" route="/api/driver/confirm/new-phone" :response-codes="[200]">
    <template v-slot:description>
تأكيد رقمى الجديد
    </template>
    <template v-slot:body>
         <api-ref-item name="new_phone_code" :required="true" type="string">
            the code that is sent to your mobile
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' => 'operation done successfully',
}
</pre>
</template>
<template v-slot:403>
<pre>
{
"message": "invalid code"
}
</pre>
</template>
</api-ref>

