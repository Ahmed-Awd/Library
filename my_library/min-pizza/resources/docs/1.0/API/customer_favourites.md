##    متابعة العميل للوجبة
عرض الوجبات التي تم متابعتها من قبل العميل
<api-ref title="get all items in the favorites" verb="get" route="api/my-favourite-items" :response-codes="[200]">
<template v-slot:description>
عرض الوجبات التي تم متابعتها من قبل العميل وتظهر للعميل نفسه
</template>
<template v-slot:body>
</template>
<template v-slot:headers>
<api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
Accept json responses
</api-ref-item>
</template>
<template v-slot:200>
<pre>
{
'favoriteItems' => [array],
}
</pre>
</template>
</api-ref>


##  أصافة متابعة لوجبة من العميل أو الغاؤها

<api-ref title="add item to favourites" verb="post" route="api/items/favourite/{item_id}" :response-codes="[200]">
    <template v-slot:description>
اضافة او الغاء متابعة للوجبة
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'item favourite status updated successfully',
}
</pre>
</template>
</api-ref>

