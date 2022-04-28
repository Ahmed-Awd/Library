## قائمة الاصناف

<api-ref title="get all categories" verb="get" route="/api/categories" :response-codes="[200]">
    <template v-slot:description>
    جلب كل الاصناف من قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>

{
'categories' => [array], }
</pre>
</template>
</api-ref>

## اضافة صنف

<api-ref title="add new category" verb="post" route="/api/categories" :response-codes="[200]">
    <template v-slot:description>
اضافة صنف جديد
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="true" type="json">
       the category name (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
        <api-ref-item name="is_active" :required="true" type="number">
            The is active 0 or 1
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

## عرض الصنف

<api-ref title="get category data" verb="get" route="/api/categories/{category}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات الصنف من  قاعدة البيانات
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

## تعديل صنف

<api-ref title=" update category" verb="put" route="/api/categories/{category}" :response-codes="[200]">
    <template v-slot:description>
تعديل بيانات صنف 
    </template>
    <template v-slot:body>
      <api-ref-item name="name" :required="true" type="json">
            the category name (in english and it will be auto translated to arabic and swedish) 
        </api-ref-item>
        <api-ref-item name="is_active" :required="true" type="number">
            The is active 0 or 1
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

### حذف الصنف

<api-ref title=" delete category data" verb="delete" route="/api/categories/{category}" :response-codes="[200]">
    <template v-slot:description>
  حذف الصنف   
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

### عرض مطاعم الصنف

<api-ref title="get restaurants of category" verb="get" route="/api/category/restaurants/{category}" :response-codes="[200]">
    <template v-slot:description>
    جلب بيانات المطاعم الخاصة بالنوع
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'category' =>{category},
'restaurants' => {array}
}
</pre>
</template>
</api-ref>
