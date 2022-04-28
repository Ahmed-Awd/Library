## قائمة الخيارات المؤقته 

<api-ref title="get all option templates" verb="get" route="api/option-template" :response-codes="[200]">
    <template v-slot:description>
    جلب كل أنواع الخيارات المؤقته من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'templates' => [array],
}
</pre>
</template>
</api-ref>

##  اضافة خيار مؤقت

option_secondaries  عبارة عن مصفوفة تحتوي على 

| # | variable   | desc |
| : |   :-   |  :  |
| 1 | secondary_option_id |  option category must be  secondary type  |
| 2 | primary_option_value_id   | option value of primary option category  |
| 2 | secondary_option_value_id   | option value of secondary option category  |
| 2 | price   | price  |
| 2 | use_default   | true or false  |

<api-ref title="add option templates" verb="post" route="api/option-template" :response-codes="[200]">
    <template v-slot:description>
اضافة خيار مؤقت جديد
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="true" type="json">
            The name is in english and it will be auto translated to arabic and swedish
        </api-ref-item>
        <api-ref-item name="primary_option_id" :required="true" type="number">
            primary option id is id of option category and must be  primary type
        </api-ref-item>
         <api-ref-item name="option_secondaries" :required="true" type="array">
             option_secondaries is array of content 
             secondary_option_id => option category must be  secondary type
             primary_option_value_id => option value of primary option category
             secondary_option_value_id => option value of secondary option category
             price => price 
             use_default => boolean
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'templates created successfully',
}
</pre>
</template>
</api-ref>

##  عرض  خيار مؤقت

<api-ref title="get option template data" verb="get" route="api/option-template/{optionTemplate}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات خيار مؤقت  من  قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'template' =>{template},
}
</pre>
</template>
</api-ref>

##  تعديل خيار مؤقت

<api-ref title=" update option template" verb="put" route="api/option-template/{optionTemplate}" :response-codes="[200]">
    <template v-slot:description>
تعديل بيانات  خيار مؤقت 
    </template>
    <template v-slot:body>,
      <api-ref-item name="name" :required="true" type="json">
            The name is in english and it will be auto translated to arabic and swedish
        </api-ref-item>
        <api-ref-item name="primary_option_id" :required="true" type="number">
            primary option id is id of option category and must be  primary type
        </api-ref-item>
         <api-ref-item name="option_secondaries" :required="true" type="array">
             option_secondaries is array of content 
             secondary_option_id => option category must be  secondary type
             primary_option_value_id => option value of primary option category
             secondary_option_value_id => option value of secondary option category
             price => price 
             use_default => boolean
        </api-ref-item>
    <template v-slot:200>
        <pre>
{
'message' =>'option template  updated successfully',
}
</pre>
</template>
</api-ref>

###  حذف الخيار مؤقت

<api-ref title=" delete option template data" verb="delete" route="api/option-template/{optionTemplate}" :response-codes="[200]">
    <template v-slot:description>
  حذف الخيار مؤقت   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  category  deleted successfully",
}
        </pre>
    </template>
</api-ref>

