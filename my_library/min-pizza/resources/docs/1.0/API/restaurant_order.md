## الطلبات
العمليات على الطلبات  من جهة صاحب المطعم 

<api-ref title="get all  restaurant orders" verb="get" route="api/restaurant/orders" :response-codes="[200]">
    <template v-slot:description>
    جلب كل الطلبات الخاصة بالمطعم
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


## جلب الطلبيات المرفوضة

<api-ref title="get cancel  restaurant orders" verb="get" route="api/restaurant/orders/cancel" :response-codes="[200]">
    <template v-slot:description>
    جلب  الطلبات المرفوضة  بواسطة المطعم
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

<api-ref title="get order data" verb="get" route="api/restaurant/orders/{order}" :response-codes="[200]">
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
 اثناء الموافقة يمكن لصاحب المطعم تغير وقت تحضير الطلبية
<api-ref title="accept order" verb="post" route="api/restaurant/orders/{order}/accept_order" :response-codes="[200]">
    <template v-slot:description>
الموافقة على الطلبية
    </template>
    <template v-slot:body>
    <api-ref-item name="address_id" :required="true" type="number">
            address id is id address
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'change status successfully',
}
</pre>
</template>
</api-ref>

##  رفض  الطلبية 

<api-ref title="reject order" verb="post" route="api/restaurant/orders/{order}/cancel_order" :response-codes="[200]">
    <template v-slot:description>
رفض الطلبية
    </template>
    <template v-slot:body>
    <api-ref-item name="refuse_reason" :required="true" type="string">
            refuse_reason is reason reject the order
        </api-ref-item>
        <api-ref-item name="refuse_comment" :required="false" type="string">
            refuse_comment in note 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'change status successfully',
}
</pre>
</template>
</api-ref>

##  الطلبية جاهزة 
في حال كان service_info_type => 1 معناته الطلبية جاهزه للتوصيل 
  واذا كانت service_info_type => 0معناتها ان الطلبية جاهزة للاستلام  

<api-ref title="order ready to delivary/takeaway" verb="post" route="api/restaurant/orders/{order}/ready_to_delivered" :response-codes="[200]">
    <template v-slot:description>
الطلبية جاهزة
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