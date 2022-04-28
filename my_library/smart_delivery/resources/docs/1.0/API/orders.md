# الطلبات

## مقدمة

الطلبات هيا اكبر جزء فى هذا المشروع تم شرحه فى اكثر من جزء سابق

### [آلية الطلب](/{{route}}/{{version}}/order)

### [حالات الطلب](/{{route}}/{{version}}/order-states)

## صاحب المطعم

يمكن لصاحب المطعم ان يضيف او يعرض الطلبات الخاصة به و ايضا ان يقوم بتقييم الطلب

### جلب احصائية لكل الطلبيات

<api-ref title="get all store orders statistics" verb="get" route="/api/store/statistics" :response-codes="[200,401]">
    <template v-slot:description>
   الاحصائية  
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "data":{
        "Pending": 3,
        "On_the_way": 1,
        "Delivering": 0,
        "Delivered": 0,
        "Canceled": 0,
        "Suspond": 0
        },
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>

### جلب كل الطلبيات ماعدا المعلقة

<api-ref title="get all store orders" verb="get" route="/api/store/order?range={something}" :response-codes="[200,401]">
    <template v-slot:description>
  كل الطلبيات 
    </template>
    <template v-slot:body>
        <api-ref-item name="status[]" :required="false" type="array">
          can select more then one status
        </api-ref-item>
        <api-ref-item name="range" :required="false" type="string">
          this is a route parameter you can add to filter orders by specific dates or ranged dates:
              <br>  
            (today,this-week,prev-week,this-month,prev-month)
<br>
            or
<br>
            (range=2020-03-05,2021-04-12)

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
    "orders": "Orders",
    "suspondedCount": 5,
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>

### جلب الطلبيات المعلقة

<api-ref title="get  store orders susponded " verb="get" route="/api/store/susponded" :response-codes="[200,401]">
    <template v-slot:description>
  كل الطلبيات 
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "orders": "Orders",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>

### جلب تفاصيل طلبية

<api-ref title="get specific order details" verb="get" route="/api/store/order/{order_id}" :response-codes="[200,401]">
    <template v-slot:description>
 تفاصيل طلبية 
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "orders": "Orders",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>

### اضافة طلبية جديدة

<api-ref title="create new order" verb="post" route="/api/store/order" :response-codes="[200,401]">
    <template v-slot:description>
 ادخال بيانات  طلبية جديدة 
    </template>
    <template v-slot:body>
        <api-ref-item name="customer_name" :required="true" type="string">
            The customer name 
        </api-ref-item>
        <api-ref-item name="location" :required="true" type="array">
            The array contain lat and lng 
        </api-ref-item>
        <api-ref-item name="customer_address" :required="true" type="string">
            The customer address 
        </api-ref-item>
        <api-ref-item name="total_price" :required="true" type="number">
            total price 
        </api-ref-item>
    <api-ref-item name="customer_mobile" :required="true" type="string">
          customer mobile
        </api-ref-item>
    <api-ref-item name="expected_time" :required="true" type="datetime">
            The expected time
        </api-ref-item>
    <api-ref-item name="building_no" :required="false" type="number">
            The building no 
        </api-ref-item>
    <api-ref-item name="apartment_no" :required="false" type="number">
            The apartment no
        </api-ref-item>
    <api-ref-item name="comment" :required="false" type="string">
            The comment 
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
    "message": "A new Order  created",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>

###  تعديل  الطلبية

<api-ref title="update order " verb="put" route="/api/store/order/{order_id}" :response-codes="[200,401]">
    <template v-slot:description>
 تعديل  الطلبية 
    </template>
    <template v-slot:body>
        <api-ref-item name="customer_name" :required="false" type="string">
            The customer name 
        </api-ref-item>
        <api-ref-item name="customer_address" :required="false" type="string">
            The customer address 
        </api-ref-item>
        <api-ref-item name="total_price" :required="false" type="number">
            total price 
        </api-ref-item>
    <api-ref-item name="customer_mobile" :required="false" type="string">
          customer mobile
        </api-ref-item>
    <api-ref-item name="expected_time" :required="false" type="datetime">
            The expected time
        </api-ref-item>
    <api-ref-item name="building_no" :required="false" type="number">
            The building no 
        </api-ref-item>
    <api-ref-item name="apartment_no" :required="false" type="number">
            The apartment no
        </api-ref-item>
    <api-ref-item name="comment" :required="false" type="string">
            The comment 
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
    "orders": "Orders",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>


### اعادة طلب الطلبية الموقفة

<api-ref title=" reorder the suspond order" verb="get" route="/api/store/reorder/{order_id}" :response-codes="[200,401]">
    <template v-slot:description>
ترجع رسالة توضح هل تم تغير الحالة او ان رصيدك غير كافي  
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "Reordered successfully",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>


### الغاء الطلبية

<api-ref title=" cancel the order" verb="get" route="/api/store/cancel/{order}" :response-codes="[200,400]">
    <template v-slot:description>
ترجع رسالة توضح هل تم الغاء او لا  
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "Cancel order successfully",
}
        </pre>
    </template>
     <template v-slot:400>
        <pre>
{
    "message": "You can't cancel order because the order status is",
}
        </pre>
    </template>
</api-ref>


###تحديد سعر طلبية للاستعلام فقط

<api-ref title="estimate new order" verb="post" route="/api/store/order/estimate" :response-codes="[200,401]">
    <template v-slot:description>
 ادخال بيانات  طلبية جديدة 
    </template>
    <template v-slot:body>
        <api-ref-item name="customer_name" :required="true" type="string">
            The customer name 
        </api-ref-item>
        <api-ref-item name="location" :required="true" type="array">
            The array contain lat and lng 
        </api-ref-item>
        <api-ref-item name="customer_address" :required="true" type="string">
            The customer address 
        </api-ref-item>
        <api-ref-item name="total_price" :required="true" type="number">
            total price 
        </api-ref-item>
    <api-ref-item name="customer_mobile" :required="true" type="string">
          customer mobile
        </api-ref-item>
    <api-ref-item name="expected_time" :required="true" type="datetime">
            The expected time
        </api-ref-item>
    <api-ref-item name="building_no" :required="false" type="number">
            The building no 
        </api-ref-item>
    <api-ref-item name="apartment_no" :required="false" type="number">
            The apartment no
        </api-ref-item>
    <api-ref-item name="comment" :required="false" type="string">
            The comment 
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
    "deliveryPrice": "delivery price will be taken from customer",
    "storeFee": "credit will be taken from store",
    "originalPrice": "original price that was set in the order details",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Dose not have store",
}
        </pre>
    </template>
</api-ref>

### تقيم الطلبية التي تم تسليمها

<api-ref title=" reorder the suspond order" verb="post" route="/store/order/rate/{order}" :response-codes="[200,401]">
    <template v-slot:description>
ترجع رسالة توضح هل تم  التقيم او ان الطلب غير موجود 
    </template>
    <template v-slot:body>
        <api-ref-item name="rate" :required="true" type="number">
            rate 
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
    "message":  "order 28 rated successfully",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Does not have order",
}
        </pre>
    </template>
</api-ref>

## السائق

السائق يقوم أستقبال الطلب و متابعة العملية كما شرح بالسابق

### جلب احصائية لكل الطلبيات

<api-ref title="get all driver orders statistics" verb="get" route="/api/driver/statistics" :response-codes="[200,401]">
    <template v-slot:description>
   الاحصائية  
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "data":{
        "Pending": 3,
        "On_the_way": 1,
        "Delivering": 0,
        "Delivered": 0,
        "Canceled": 0,
        },
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Unauthorized",
}
        </pre>
    </template>
</api-ref>

### جلب الطلبات التي مع السائق

<api-ref title="get all already done orders for driver" verb="get" route="/api/driver/order/" :response-codes="[200,401]">
    <template v-slot:description>
  كل الطلبيات 
    </template>
     <template v-slot:body>
        <api-ref-item name="status[]" :required="false" type="array">
          can select more then one status
        </api-ref-item>
        <api-ref-item name="range" :required="false" type="string">
          this is a route parameter you can add to filter orders by specific dates or ranged dates
              <br>  
            (today,this-week,prev-week,this-month,prev-month)
<br>
            or
<br>
            (range=2020-03-05,2021-04-12)

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
    "orders": "Orders",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Unauthorized",
}
        </pre>
    </template>
</api-ref>

### جلب الطلبات الجديدة

<api-ref title="get all new orders for driver" verb="get" route="/api/driver/order/new" :response-codes="[200,401]">
    <template v-slot:description>
  كل الطلبيات 
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "orders": "Orders",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Unauthorized",
}
        </pre>
    </template>
</api-ref>

### جلب تفاصيل الطلبية

<api-ref title="get specific order details" verb="get" route="/api/driver/order/{order}" :response-codes="[200,401]">
    <template v-slot:description>
 بيانات طلبية  
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "orders": "Orders",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Unauthorized",
}
        </pre>
    </template>
</api-ref>

### الموافقة على الطلبية

<api-ref title="accept the order" verb="get" route="/api/driver/accept/{order}" :response-codes="[200,401]">
    <template v-slot:description>
 بيانات طلبية  
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message" => "accept order successfull"
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Unauthorized",
}
        </pre>
    </template>
</api-ref>

### API for delivery of  the order

<api-ref title="delivery the order" verb="post" route="/api/driver/delivery/{order}" :response-codes="[200,401]">
    <template v-slot:description>
when the driver receives the order from the store and scan the qr code
    </template>
    <template v-slot:body>
        <api-ref-item name="qr_code" :required="true" type="number" example="application/json">
once the qr code sent from your request matches the order qr code the delivery process will proceed to the next step
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
    "message": "delivery order successfull",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Unauthorized",
}
        </pre>
    </template>
</api-ref>

### API after the order is delivered

once the order is delivered the delivery process will proceed to the next step

<api-ref title="the order is delivered" verb="get" route="/api/driver/delivered/{order}" :response-codes="[200,401]">
    <template v-slot:description>
when the driver delivers the order to the customer     
</template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "delivered order successfull",
}
        </pre>
    </template>
     <template v-slot:401>
        <pre>
{
    "message": "Unauthorized",
}
        </pre>
    </template>
</api-ref>
