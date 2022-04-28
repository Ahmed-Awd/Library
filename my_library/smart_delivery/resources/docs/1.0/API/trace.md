##  تغيير المكان

<api-ref title="change my current location" verb="post" route="/api/update/my/location" :response-codes="[200,403]">
    <template v-slot:description>
        تغيير   النقطة الحالية
    </template>
    <template v-slot:body>
         <api-ref-item name="lat" :required="true" type="numeric">
            lat of the point
        </api-ref-item>
         <api-ref-item name="lng" :required="true" type="numeric">
            lng of the point
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
"message": ""
}
</pre>
</template>
</api-ref>
