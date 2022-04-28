## قائمة نوع الأختيرات

<api-ref title="get all option categories" verb="get" route="api/option-categories" :response-codes="[200]">
    <template v-slot:description>
    جلب كل أنواع الأختيرات من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'categories' => [array],
}
</pre>
</template>
</api-ref>

##  اضافة تصنيف

<api-ref title="add new category" verb="post" route="api/option-categories" :response-codes="[200]">
    <template v-slot:description>
اضافة تصنيف جديد
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="true" type="json">
            The name is in english and it will be auto translated to arabic and swedish
        </api-ref-item>
        <api-ref-item name="selection" :required="true" type="enum">
             single or multiple
        </api-ref-item>
         <api-ref-item name="is_primary" :required="true" type="boolean">
             is primary
        </api-ref-item>
         <api-ref-item name="max_selectable" :required="false" type="number">
            max selectable one if is primary
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'category created successfully',
}
</pre>
</template>
</api-ref>

##  عرض  التصنيف

<api-ref title="get category data" verb="get" route="api/option-categories/{category}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات التصنيف من  قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'category' =>{category},
}
</pre>
</template>
</api-ref>

##  تعديل تصنيف

<api-ref title=" update category" verb="put" route="api/option-categories/{category}" :response-codes="[200]">
    <template v-slot:description>
تعديل بيانات تصنيف 
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="false" type="json">
            The name is in english and it will be auto translated to arabic and swedish
        </api-ref-item>
        <api-ref-item name="selection" :required="false" type="enum">
             single or multiple
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'category updated successfully',
}
</pre>
</template>
</api-ref>

###  حذف التصنيف

<api-ref title=" delete category data" verb="delete" route="api/option-categories/{category}" :response-codes="[200]">
    <template v-slot:description>
  حذف التصنيف   
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

## قائمة  الأختيارات

<api-ref title="get all option categories" verb="get" route="api/option-value/category/{category}" :response-codes="[200]">
    <template v-slot:description>
    جلب كل  الأختيارات من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'values' => [array],'category'=>[object]
}
</pre>
</template>
</api-ref>

##  اضافة اختيار

<api-ref title="add new value" verb="post" route="api/option-value" :response-codes="[200]">
    <template v-slot:description>
اضافة اختيار جديد
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="true" type="json">
            The name in it's original lang (no translation)
        </api-ref-item>
        <api-ref-item name="price" :required="true" type="decimal">
             price of option ex:(50.50,4.25,3.00)
        </api-ref-item>
        <api-ref-item name="option_category_id" :required="true" type="number">
             option category id
        </api-ref-item>
        <api-ref-item name="min_count" :required="false" type="number">
            min count is 1 if option category is primary
        </api-ref-item>
        <api-ref-item name="max_count" :required="false" type="number">
            max count is 1 if option category is primary
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'option created successfully',
}
</pre>
</template>
</api-ref>

##  عرض  الاختيار

<api-ref title="get option data" verb="get" route="api/option-value/{option}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات الاختيار من  قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'value' =>{value},
}
</pre>
</template>
</api-ref>

##  تعديل اختيار

<api-ref title=" update option" verb="patch" route="api/option-value/{option}" :response-codes="[200]">
    <template v-slot:description>
تعديل بيانات اختيار 
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="true" type="json">
            The name in it's original lang (no translation)
        </api-ref-item>
        <api-ref-item name="price" :required="true" type="decimal">
             price of option ex:(50.50,4.25,3.00)
        </api-ref-item>
        <api-ref-item name="option_category_id" :required="true" type="number">
             option category id
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'option updated successfully',
}
</pre>
</template>
</api-ref>

###  حذف الاختيار

<api-ref title=" delete option data" verb="delete" route="api/option-value/{option}" :response-codes="[200]">
    <template v-slot:description>
  حذف الاختيار   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  option  deleted successfully",
}
        </pre>
    </template>
</api-ref>
