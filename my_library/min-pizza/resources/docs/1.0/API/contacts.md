## قائمة التواصل معنا

<api-ref title="get all contacts" verb="get" route="/api/contacts" :response-codes="[200]">
    <template v-slot:description>
    جلب كل رسائل التواصل معنا من قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>
{
        'contacts' => [array],
}
        </pre>
    </template>
</api-ref>

##  اضافة التواصل معنا 

<api-ref title="add new contact" verb="post" route="/api/contacts" :response-codes="[200]">
    <template v-slot:description>
اضافة رساىلة التواصل معنا 
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="true" type="string">
       the contact name
        </api-ref-item>
        <api-ref-item name="email" :required="true" type="email">
       the contact email
        </api-ref-item>
        <api-ref-item name="phone" :required="true" type="string">
       the contact phone
        </api-ref-item>
        <api-ref-item name="message" :required="true" type="string">
       the contact message
        </api-ref-item>
       
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'contact created successfully',
}
</pre>
</template>
</api-ref>

##  عرض  التواصل معنا 

<api-ref title="get contact data" verb="get" route="/api/contacts/{contact}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات رسالة التواصل معنا  من  قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'contact' =>{contact},
}
</pre>
</template>
</api-ref>


###  حذف الرسالة  

<api-ref title=" delete contact data" verb="delete" route="/api/contacts/{contact}" :response-codes="[200]">
    <template v-slot:description>
  حذف رسالة التواصل معنا   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  contact  deleted successfully",
}
        </pre>
    </template>
</api-ref>

