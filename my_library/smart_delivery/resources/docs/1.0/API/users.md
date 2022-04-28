# المستخدمين


##مقدمة
هذا التطبيق يستهدف ثلاث أنواع من المستخدمين كما شرح فى السابق و هم الأدمن و السائق و صاحب المتجر

الأدمن و جميع التعاملات معه من خلاص الأدمن بانل فقط ولا يوجد معه اى تقاعل على مستوى التطبيقات

##صاحب المطعم

صاحب المطعم يتم أضافته عن طريق الأدمن ولا يوجد اى طريقة register فى التطبيق له و يقوم فقط بالدخول الذى سيتم شرحه فى قسم لاحق.

##السائق

يمكن أضافة السائق من كل من ال admin panel و ايضا التطبيق الخاص بالسائق معا فارف انه لا يكون مفعلا فى حالة اذا قام ب ال register من التطبيق و يجب تفعيله عن طريق ال admin panel

##تسجيل سائق جديد

<api-ref title="register a new driver" verb="post" route="/api/driver/register" :response-codes="[200]">
    <template v-slot:description>
بيانات السائق 
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            The driver name 
        </api-ref-item>
        <api-ref-item name="username" :required="true" type="string">
            The username 
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="email">
            The user email 
        </api-ref-item>
         <api-ref-item name="phone" :required="true" type="integer">
            The user phone 
        </api-ref-item>
         <api-ref-item name="town" :required="true" type="numeric">
            town id
        </api-ref-item>
         <api-ref-item name="country_code" :required="true" type="integer">
            The user country code 
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The password 
        </api-ref-item>
        <api-ref-item name="password_confirmation" :required="true" type="string">
            The same as password 
        </api-ref-item>
        <api-ref-item name="personal_photo" :required="true" type="file:image(png,jpg,jpeg)">
            personal photo of driver.
        </api-ref-item>
        <api-ref-item name="license_photo" :required="true" type="file:image(png,jpg,jpeg)">
           license photo required to make sure driver is licensed. 
        </api-ref-item>
        <api-ref-item name="vehicle_photo" :required="true" type="file:image(png,jpg,jpeg)">
           vehicle photo required to make sure vehicle is good. 
        </api-ref-item>      
        <api-ref-item name="vehicle_license_photo" :required="true" type="file:image(png,jpg,jpeg)">
            vehicle license photo required to make sure vehicle is licensed. 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "your account created successfully,please wait for activation",
}
        </pre>
    </template>
</api-ref>

##تعديل بيانات السائق 

<api-ref title="update a data driver" verb="post" route="/api/driver/update" :response-codes="[200]">
    <template v-slot:description>
بيانات السائق 
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
            The driver name 
        </api-ref-item>
        <api-ref-item name="username" :required="true" type="string">
            The username 
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="email">
            The user email 
        </api-ref-item>
         <api-ref-item name="phone" :required="true" type="integer">
            The user phone 
        </api-ref-item>
         <api-ref-item name="country_code" :required="true" type="integer">
            The user country code 
        </api-ref-item>
        <api-ref-item name="personal_photo" :required="true" type="file:image(png,jpg,jpeg)">
            personal photo of driver.
        </api-ref-item>
        <api-ref-item name="license_photo" :required="true" type="file:image(png,jpg,jpeg)">
           license photo required to make sure driver is licensed. 
        </api-ref-item>
        <api-ref-item name="vehicle_photo" :required="true" type="file:image(png,jpg,jpeg)">
           vehicle photo required to make sure vehicle is good. 
        </api-ref-item>      
        <api-ref-item name="vehicle_license_photo" :required="true" type="file:image(png,jpg,jpeg)">
            vehicle license photo required to make sure vehicle is licensed. 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "data has been created successfully,please wait for accepted",
}
        </pre>
    </template>
</api-ref>


## تسجيل الدخول الخاصة بالمطعم 

<api-ref title="login to the app" verb="post" route="/api/store/login" :response-codes="[200,401]">
    <template v-slot:description>
يمكن لصاحب المطعم الدخول على التطبيق عن طريق ال api
    </template>
    <template v-slot:body>
        <api-ref-item name="email" :required="true" type="string">
           email or username
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The password 
        </api-ref-item>
        <api-ref-item name="notification_token" :required="true" type="string">
            the mobile notification token
        </api-ref-item>
         <api-ref-item name="os_type" :required="true" type="enum">
            android,ios 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        access_token' => '1|hfdjhfjd54512kjjkdfhkjf',
        'token_type' => 'Bearer',
        'store' => 'store',
        'user' => 'user',
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


## تسجيل الدخول الخاصة بالشركات

<api-ref title="login to the app" verb="post" route="/api/company/login" :response-codes="[200,401]">
    <template v-slot:description>
يمكن للحساب الخارجى للشركات الدخول على التطبيق عن طريق ال api
    </template>
    <template v-slot:body>
        <api-ref-item name="email" :required="true" type="string">
           email or username
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The password 
        </api-ref-item>
        <api-ref-item name="notification_token" :required="true" type="string">
            the mobile notification token
        </api-ref-item>
         <api-ref-item name="os_type" :required="true" type="enum">
            android,ios 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        access_token' => '1|hfdjhfjd54512kjjkdfhkjf',
        'token_type' => 'Bearer',
        'user' => 'user',
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
## تسجيل الدخول الخاصة بالسائق 

<api-ref title="login to the app" verb="post" route="/api/driver/login" :response-codes="[200,401]">
    <template v-slot:description>
يمكن للسائق الدخول على التطبيق عن طريق ال api
    </template>
    <template v-slot:body>
       <api-ref-item name="phone" :required="true" type="integer">
            The user phone 
        </api-ref-item>
         <api-ref-item name="country_code" :required="true" type="integer">
            The user country code 
        </api-ref-item>
        <api-ref-item name="password" :required="true" type="string">
            The password 
        </api-ref-item>
        <api-ref-item name="notification_token" :required="true" type="string">
            the mobile notification token
        </api-ref-item>
         <api-ref-item name="os_type" :required="true" type="enum">
            android,ios 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        access_token' => '1|hfdjhfjd54512kjjkdfhkjf',
        'token_type' => 'Bearer',
        'user' => 'user',
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

## انشاء كود تحقق 

<api-ref title="login to the app" verb="get" route="/api/code/generate" :response-codes="[200,401]">
    <template v-slot:description>
يمكن للسائق انشاء كود تحقق عن طريق ال api
    </template>
    <template v-slot:body>
        <api-ref-item name="notification_token" :required="true" type="string">
            the mobile notification token
        </api-ref-item>
         <api-ref-item name="os_type" :required="true" type="enum">
            android,ios 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        'message' => 'generate code successfully',
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


##  التحقق من الكود  

<api-ref title="login to the app" verb="post" route="/api/code/check" :response-codes="[200,400,402]">
    <template v-slot:description>
يمكن للسائق التحقق من الكود  عن طريق ال api
    </template>
    <template v-slot:body>
        <api-ref-item name="code" :required="true" type="string">
           code
        </api-ref-item>
          <api-ref-item name="notification_token" :required="true" type="string">
            the mobile notification token
        </api-ref-item>
         <api-ref-item name="os_type" :required="true" type="enum">
            android,ios 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        'message' => 'login  successfully',
}
        </pre>
    </template>
    <template v-slot:402>
        <pre>
{
    "message": "try again"
}
        </pre>
    </template>
    <template v-slot:400>
        <pre>
{
    "message": "you exceeded maximum number of attempts to write code"
}
        </pre>
    </template>
</api-ref>



##الخروج

<api-ref title="logout from the app" verb="post" route="/api/logout" :response-codes="[200,401]">
    <template v-slot:description>
logout throw app 
    </template>
    
    <template v-slot:200>
        <pre>
{
        'message' => '“Logged out successfully'
}
        </pre>
    </template>
    <template v-slot:401>
        <pre>
{
    "message": "Unauthorized"
}
        </pre>
    </template>
</api-ref>

##جلب بيانات صاحب المطعم 

<api-ref title="get store data " verb="get" route="/api/store/data" :response-codes="[200,401]">
    <template v-slot:description>
logout throw app 
    </template>
    
    <template v-slot:200>
        <pre>
{
        'store' => 'store'
        'user' => 'user'
}
        </pre>
    </template>
    <template v-slot:401>
        <pre>
{
    "message": "Unauthorized"
}
        </pre>
    </template>
</api-ref>
##جلب بيانات السائق  

<api-ref title="get driver data " verb="get" route="/api/driver/data" :response-codes="[200,401]">
    <template v-slot:description>
logout throw app 
    </template>
    
    <template v-slot:200>
        <pre>
{
        'user' => 'user'
}
        </pre>
    </template>
    <template v-slot:401>
        <pre>
{
    "message": "Unauthorized"
}
        </pre>
    </template>
</api-ref>


##جلب بيانات الشركة

<api-ref title="get company data " verb="get" route="/api/company/data" :response-codes="[200,401]">
    <template v-slot:description>
logout throw app 
    </template>

    <template v-slot:200>
        <pre>
{
'user' => 'user'
}
</pre>
</template>
<template v-slot:401>
<pre>
{
"message": "Unauthorized"
}
</pre>
</template>
</api-ref>

##تغيير حالة السائق متاح او غير متاح

<api-ref title="get store data " verb="post" route="api/driver/change-availability" :response-codes="[200,401]">
    <template v-slot:description>
logout throw app
    </template>

    <template v-slot:200>
        <pre>
{
"message": "your account is now available or un available"
}
</pre>
</template>
<template v-slot:401>
<pre>
{
"message": "Unauthorized"
}
</pre>
</template>
</api-ref>


##سجل المعاملات الخاص بالمستخدم

<api-ref title="get transactions history" verb="get" route="/api/transaction/history/{type?}" :response-codes="[200,401]">
    <template v-slot:description>
get all your outgoing and ingoing transactions
/api/transaction/history/outgoing for outgoing transactions
<br>
/api/transaction/history/ingoing for ingoing  transactions
<br>
/api/transaction/history for all  transactions

    </template>
     <template v-slot:body>
        <api-ref-item name="range" :required="false" type="string">
          this is a route parameter you can add to filter orders by specific dates or ranged dates
              <br>  
            (today,this-week,prev-week,this-month,prev-month)
<br>
            or
<br>
            (range=2020-03-05,2021-04-12)

        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
"created_at": "2021-06-18T21:01:51.000000Z",
"value": 169,
"status": "outgoing"
}
</pre>
</template>
<template v-slot:401>
<pre>
{
"message": "Unauthorized"
}
</pre>
</template>
</api-ref>


## طلب تغير كلمة المرور فى حالة هل نسيت

<api-ref title="forgot password" verb="post" route="/api/password/forgot" :response-codes="[200,404]">
    <template v-slot:description>
يمكن لكل من السائق و صاحب المطعم ان يطلب ان يعيد ضبط كلمة المرور حال نسيانها و يتم ارسال كود خاص ب ال reset للبريد او رقم الهاتف على حسب الطلب
    </template>
    <template v-slot:body>
       <api-ref-item name="email" :required="true" type="string">
            The user email or username 
        </api-ref-item>
         <api-ref-item name="reset_by" :required="true" type="enum">
            phone,email 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        'message' => 'An email sent to {$user->email} with a reset code',
        'message' => 'a reset code sent to your phone number {$hashed_phone}'
}
        </pre>
    </template>
    <template v-slot:404>
        <pre>
{
    "message": "invalid username or email"
}
        </pre>
    </template>
</api-ref>

##  تغيير كلمة المرور بالرمز

<api-ref title="reset password" verb="post" route="/api/password/reset" :response-codes="[200,404,403]">
    <template v-slot:description>
يمكن لكل من السائق و صاحب المطعم ان يطلب ان يعيد ضبط كلمة المرور حال نسيانها و يتم ارسال كود خاص ب ال reset للبريد او رقم الهاتف على حسب الطلب
    </template>
    <template v-slot:body>
       <api-ref-item name="email" :required="true" type="string">
            The user email or username 
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
        'message' => 'password changed successfully',
}
        </pre>
    </template>
    <template v-slot:404>
        <pre>
{
    "message": "invalid username or email"
}
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

##  تغيير اللغة

<api-ref title="change my language" verb="post" route="/api/change-lang" :response-codes="[200,403]">
    <template v-slot:description>
        تغيير اللغة الحالية
    </template>
    <template v-slot:body>
         <api-ref-item name="language" :required="true" type="string">
            in : ar , tr , en
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' => 'your current language changed',
}
</pre>
</template>
<template v-slot:403>
<pre>
{
"message": ""
}
</pre>
</template>
</api-ref>
