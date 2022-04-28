#social
### التسجيل الدخول عن طريق السوشيال

<api-ref title="social login to the app" verb="post" route="/api/social-login" :response-codes="[200]">
    <template v-slot:description>
يمكن للمستخدم الدخول بأستخدام السوشيل الى التطبيق عن طريق ال api
    </template>
    <template v-slot:body>
        <api-ref-item name="name" :required="true" type="string">
           name of the user 
        </api-ref-item>
        <api-ref-item name="social_id" :required="true" type="string">
           social id
        </api-ref-item>
        <api-ref-item name="social_type" :required="true" type="string">
           in (facebook,google,apple)
        </api-ref-item>
        <api-ref-item name="notification_token" :required="true" type="string">
            the mobile notification token
        </api-ref-item>
        <api-ref-item name="default_lang" :required="true" type="enum">
            ar,en,sv 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
        access_token' => '1|hfdjhfjd54512kjjkdfhkjf','token_type' => 'Bearer','user' => 'user',
}
        </pre>
    </template>
</api-ref>
