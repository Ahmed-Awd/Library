## الطلبات
العمليات على الطلبات  من جهة السائق 

<api-ref title="get new orders" verb="get" route="api/driver/orders" :response-codes="[200]">
    <template v-slot:description>
    جلب كل الطلبات  الجديدة
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'orders' => [array],
}
</pre>
</template>
</api-ref>

##  عرض  طلبات السائق 

<api-ref title="get my order " verb="get" route="api/driver/my-orders" :response-codes="[200]">
    <template v-slot:description>
جلب بيانات الطلبية 
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'orders' => [array],
}
</pre>
</template>
</api-ref>


##  عرض بيانات الطلبية 

<api-ref title="get order data" verb="get" route="api/driver/orders/{order}" :response-codes="[200]">
    <template v-slot:description>
جلب بيانات الطلبية 
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'order' =>{order},
}
</pre>
</template>
</api-ref>

##  الموافقة على الطلبية 

<api-ref title="accept order" verb="post" route="api/driver/orders/{order}/accept_order" :response-codes="[200]">
    <template v-slot:description>
الموافقة على الطلبية
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'change status successfully',
}
</pre>
</template>
</api-ref>

##   استلام  الطلبية من المطعم
عند استلام الطلبية تتغير الحالة في الطريق
<api-ref title="on the way order" verb="post" route="api/driver/orders/{order}/on_the_way" :response-codes="[200]">
    <template v-slot:description>
استلام  الطلبية من المطعم
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'order change status to On The Way successfully',
}
</pre>
</template>
</api-ref>

##     السائق وصل عند الزبون 
تتغير الحالة ان الطلبية جاهزة للاخذ من السائق
<api-ref title="ready for takeaway order" verb="post" route="api/driver/orders/{order}/ready_for_take" :response-codes="[200]">
    <template v-slot:description>
السائق وصل عند الزبون 
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'order change status to Ready For Takeaway successfully',
}
</pre>
</template>
</api-ref>