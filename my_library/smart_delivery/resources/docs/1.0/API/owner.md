
##تسجيل صاحب مطعم جديد

<api-ref title="register a new owner" verb="post" route="/api/owner/register" :response-codes="[200]">
    <template v-slot:description>
بيانات صاحب الكطعم 
    </template>
    <template v-slot:body>
        <api-ref-item name="store_name" :required="true" type="string">
            The store name 
        </api-ref-item>
        <api-ref-item name="owner_name" :required="true" type="string">
            The owner name 
        </api-ref-item>
        <api-ref-item name="owner_username" :required="true" type="string">
            The username of owner
        </api-ref-item>
        <api-ref-item name="owner_email" :required="true" type="email">
            The owner email 
        </api-ref-item>
        <api-ref-item name="town" :required="true" type="numeric">
            The town id 
        </api-ref-item>
         <api-ref-item name="country_code" :required="true" type="integer">
            The user country code 
        </api-ref-item>
         <api-ref-item name="phone" :required="true" type="integer">
            The user phone 
        </api-ref-item>  
        <api-ref-item name="address" :required="true" type="string">
            The store address 
        </api-ref-item>
         <api-ref-item name="activity_type" :required="true" type="integer">
            type of activity from type table
        </api-ref-item>
        <api-ref-item name="location" :required="true" type="array">
            The array contain lat and lng 
        </api-ref-item>

        <api-ref-item name="password" :required="true" type="string">
            The password 
        </api-ref-item>
        <api-ref-item name="password_confirmation" :required="true" type="string">
            The same as password 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "your signup success,an activation code is sent to your phone",
}
        </pre>
    </template>
</api-ref>

## طلب اعادة رمز التفعيل

<api-ref title="activation code resend" verb="post" route="/api/owner/resend-verify" :response-codes="[200,404]">
    <template v-slot:description>
 طلب اعادة رمز التفعيل    
    </template>
    <template v-slot:body>
         <api-ref-item name="country_code" :required="true" type="integer">
            The user country code 
        </api-ref-item>
         <api-ref-item name="phone" :required="true" type="integer">
            The user phone 
        </api-ref-item> 
    </template>
    <template v-slot:200>
        <pre>
{
        'message' => 'a new activation code is sent to your phone',
}
        </pre>
    </template>
    <template v-slot:404>
        <pre>
{
    "message": "invalid phone"
}
        </pre>
    </template>
</api-ref>


##تفعيل الحساب

<api-ref title="activate account" verb="post" route="/api/owner/verify" :response-codes="[200,404,403]">
    <template v-slot:description>
تفعيل الحساب           
    </template>
    <template v-slot:body>
         <api-ref-item name="country_code" :required="true" type="integer">
            The user country code 
        </api-ref-item>
         <api-ref-item name="phone" :required="true" type="integer">
            The user phone 
        </api-ref-item> 
         <api-ref-item name="activation_code" :required="true" type="string">
            the code sent to your phone via sms
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' => 'account verified successfully',
}
</pre>
</template>
<template v-slot:404>
<pre>
{
"message": "invalid account"
}
</pre>
</template>
<template v-slot:403>
<pre>
{
"message": "invalid reset code"
}
</pre>
<template v-slot:403>
<pre>
{
"message": "reset code time expired,try resend the code"
}
</pre>
</template>
</api-ref>
