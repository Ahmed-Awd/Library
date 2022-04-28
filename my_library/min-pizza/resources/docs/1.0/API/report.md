# التقارير


## عرض بيانات كل العملاء
إظهار بيانات العملاء  ليبين معلوماتهم  ويبين في الأخير العدد الكلي لهم 
<api-ref title="get all  customers data " verb="Post" route="/api/admin/report/customers" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات العملاء   
 </template>
 <template v-slot:body>
        <api-ref-item name="search" :required="false" type="string">
            search by The customer name  
        </api-ref-item>
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "data":[
        "new_customer"=>,
        "all_customer"=>,
        "has_order_customer"=>,
        "has_more_order_customer"=>,
    ],
    "customers":[array],
}
        </pre>
    </template>
</api-ref>


## عرض بيانات كل المطاعم
تظهر إحصائيات عن عدد المطاعم و أسمائها و صورها و إجمالي الطلبات منها 
<api-ref title="get all  restaurants data " verb="Post" route="/api/admin/report/restaurants" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات المطاعم   
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
    "data":[
        "new_restaurant"=>,
        "all_restaurant"=>,
    ],
    "restaurant":[array],
}
        </pre>
    </template>
</api-ref>



## عرض بيانات كل الطلبات
 تم إظهار جميع بيانات الطلبات يستطيع الادمن فلترتها بالتاريخ من والى تاريخ معين و أيضاً تكون خيارات (يوم -امس -الاسبوع الحالي الاسبوع السابق -الشهر الحالي  - الشهرالسابق  متوفره "قبل خيار الفلترة بالتاريخ")

range

| # | المتغير   | الوصف |
| : |   :-   |  :  |
| 1 | today | يرجع طلبات اليوم  |
| 2 | yesterday   | يرجع طلبات امس  |
| 3 | this-week   | يرجع طلبات هذا الاسبوع  |
| 4 | prev-week   | يرجع طلبات الاسبوع السابق  |
| 5 | this-month   | يرجع طلبات هذا الشهر  |
| 6 | prev-month   | يرجع طلبات الشهر السابق  |
| 7 | 2021-11-12,2021-11-13   | يرجع طلبات بين تاريخين  |

<api-ref title="get all  orders data " verb="Post" route="/api/admin/report/orders" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات الطلبات   
 </template>
 <template v-slot:body>
        <api-ref-item name="range" :required="false" type="string">
            range is filter the order by date  
        </api-ref-item>
        <api-ref-item name="order_status_id" :required="false" type="number">
            order_status_id is filter the order by order status   
        </api-ref-item>
        <api-ref-item name="restaurant_id" :required="false" type="number">
            restaurant_id is filter the order by restaurant 
        </api-ref-item>
        <api-ref-item name="service_info_type" :required="false" type="number">
            service_info_type is filter the order by service type (delivary or takaway) 
        </api-ref-item>
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "data":[
        "all_orders"=>,
        "delivery_orders"=>,
        "takeaway_orders"=>,
    ],
    "total_amount":,
    "filters":[array],
    "range":,
    "orders":[array],
}
        </pre>
    </template>
</api-ref>
