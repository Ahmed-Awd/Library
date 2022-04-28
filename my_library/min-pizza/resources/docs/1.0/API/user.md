## تسجيل الدخول

<api-ref title="login to the app" verb="post" route="/api/login" :response-codes="[200,401]">
    <template v-slot:description>
يمكن للمستخدم الدخول على التطبيق عن طريق ال api
    </template>
    <template v-slot:body>
        <api-ref-item name="email" :required="true" type="string">
           email 
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The password 
        </api-ref-item>
        <api-ref-item name="notification_token" :required="true" type="string">
            the mobile notification token
        </api-ref-item>
         <api-ref-item name="os_type" :required="false" type="enum">
            android,ios 
        </api-ref-item>
        <api-ref-item name="default_lang" :required="true" type="enum">
            ar,en,sv 
        </api-ref-item>
        <api-ref-item name="role" :required="true" type="string">
            super_admin , admin , driver , owner , customer 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        access_token' => '1|hfdjhfjd54512kjjkdfhkjf','token_type' => 'Bearer','user' => 'user',
}
        </pre>
    </template>
    <template v-slot:401>
        <pre>
{
    "message": "Invalid login details"
}
        </pre>
    </template>
</api-ref>

## تسجيل الخروج

<api-ref title="logout" verb="post" route="/api/logout" :response-codes="[200]">
    <template v-slot:description>
تسجيل الخروج من الحساب 
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>

{
"message": "Logged out successfully", }
</pre>
</template>
</api-ref>



### طلب تغير كلمة المرور فى حالة هل نسيت

<api-ref title="forgot password" verb="post" route="/api/password/forgot" :response-codes="[200,404]">
    <template v-slot:description>
يمكن لكل مستخدم ان يطلب ان يعيد ضبط كلمة المرور حال نسيانها و يتم ارسال كود خاص ب ال reset للبريد  على حسب الطلب
    </template>
    <template v-slot:body>
       <api-ref-item name="email" :required="true" type="string">
            The email  
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        'message' => 'An email sent to {$user->email} with a reset code',
}
        </pre>
    </template>
    <template v-slot:404>
        <pre>
{
    "message": "invalid email"
}
        </pre>
    </template>
</api-ref>

## تغيير كلمة المرور بالرمز

<api-ref title="reset password" verb="post" route="/api/password/reset" :response-codes="[200,404,403]">
    <template v-slot:description>
يمكن لكل مستخدم ان يطلب ان يعيد ضبط كلمة المرور حال نسيانها و يتم ارسال كود خاص ب ال reset للبريد  على حسب الطلب
    </template>
    <template v-slot:body>
       <api-ref-item name="email" :required="true" type="string">
            The user email 
        </api-ref-item>
         <api-ref-item name="code" :required="true" type="string">
            the code sent to your phone via sms or via email
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
'message' => 'password changed successfully', }
</pre>
</template>
<template v-slot:403>
<pre>
{
"message": "invalid reset code"
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

## تحديث بياناتك الشخصية

<api-ref title="update  your data" verb="post" route="/api/my-info" :response-codes="[200]">
    <template v-slot:description>
 تحديث بياناتك  
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
        <api-ref-item name="default_lang" :required="false" type="string in (ar,sv,en)">
            your default lang in case of notifications
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "your information updated successfully ",
}
        </pre>
    </template>
</api-ref>

###عرض بيانات حسابك

<api-ref title="get your data" verb="get" route="/api/my-info" :response-codes="[200]">
    <template v-slot:description>
 عرض بياناتك 
    </template>
     <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
    "user": "object",
}
        </pre>
    </template>
</api-ref>
## تحديث عنوانك الافتراضي 

<api-ref title="update  your default location" verb="post" route="/api/set-default-address" :response-codes="[200]">
    <template v-slot:description>
 تحديث بيانات موقعك  
    </template>
     <template v-slot:body>
   <api-ref-item name="lat" :required="true" type="numeric">
            The lat of the location 
        </api-ref-item>
        <api-ref-item name="lng" :required="false" type="numeric">
            The lng of the location 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "your information updated successfully ",
}
        </pre>
    </template>
</api-ref>
