# التقارير


## طباعة بيانات كل العملاء
طباعة بيانات العملاء  ليبين معلوماتهم  
data

| # | الاعمدة| الوصف |
| : |   :   |  :  |
| 1 | id | رقم العميل   |
| 2 | name   |اسم العميل  |
| 3 | email   | ايميل العميل  |
| 4 | phone   | رقم هاتف العميل  |
| 5 | orders_count   | عدد مرات الطلب   |
| 6 | orders_sum_total_amount   | إجمالي مبلغ طلباته  |

<api-ref title="export all  customers data " verb="Post" route="/api/admin/report/export/customers" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات العملاء   
 </template>
 <template v-slot:body>
        <api-ref-item name="data" :required="false" type="array">
            Contains columns that should appear
        </api-ref-item>
        <api-ref-item name="type" :required="false" type="string">
          type is  pdf or excel
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
    
}
        </pre>
    </template>
</api-ref>


## طباعة بيانات كل المطاعم
طباعة بيانات المطاعم     
data

| # | الاعمدة   | الوصف |
| : |   :-   |  :  |
| 1 | id | رقم المطعم   |
| 2 | name   |اسم المطعم  |
| 3 | owner_name   | اسم المالك   |
| 4 | ratings_avg_rate   | نسبة التقيم    |
| 5 | orders_count   | عدد مرات الطلب   |
| 6 | orders_sum_total_amount   | إجمالي مبلغ الطلبات  |

<api-ref title="export all  restaurants data " verb="Post" route="/api/admin/report/export/restaurants" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات المطاعم   
 </template>
 <template v-slot:body>
        <api-ref-item name="data" :required="false" type="array">
            Contains columns that should appear
        </api-ref-item>
        <api-ref-item name="type" :required="false" type="string">
          type is  pdf or excel
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
  
}
        </pre>
    </template>
</api-ref>

## طباعة بيانات كل الطلبات
طباعة بيانات الطلبات     
data

| # | الاعمدة   | الوصف |
| : |   :-   |  :  |
| 1 | order_number | رقم الطلبية   |
| 2 | created_at   |تاريخ الطلبية |
| 3 | address   | عنوان الطبية  |
| 4 | user   |  العميل    |
| 5 | restaurant   |  المطعم |
| 5 | driver   |السائق   |
| 6 | total_amount   | إجمالي مبلغ الطلبية  |
| 6 | status   |   الحالة  |
| 6 | code   |كود الخصم |

<api-ref title="export all  orders data " verb="Post" route="/api/admin/report/export/orders" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات المطاعم   
 </template>
 <template v-slot:body>
        <api-ref-item name="data" :required="false" type="array">
            Contains columns that should appear
        </api-ref-item>
        <api-ref-item name="type" :required="false" type="string">
          type is  pdf or excel
        </api-ref-item>
        <api-ref-item name="service_info_type" :required="false" type="number">
          service_info_type is type service delivery=>0 or takaway=>1
        </api-ref-item>
         <api-ref-item name="restaurant_id" :required="false" type="number">
          restaurant_id is id of restaurant
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
  
}
        </pre>
    </template>
</api-ref>



