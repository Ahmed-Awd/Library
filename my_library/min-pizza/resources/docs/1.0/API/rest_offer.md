#عروض المطاعم


### عرض بيانات كل العروض

<api-ref title="get all  restaurant offers" verb="Get" route="/api/rest-offers" :response-codes="[200]">
    <template v-slot:description>
جلب كل بيانات العروض   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "offers":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة او تعديل عرض الجديد

<api-ref title="start an offer" verb="post" route="/api/rest-offers" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  العرض  
    </template>
    <template v-slot:body>
        <api-ref-item name="restaurant_id" :required="true" type="numeric">
            the restaurant id or null in case of all restaurants
        </api-ref-item>
        <api-ref-item name="rate" :required="true" type="numeric">
            the rate of discount (rate from 1 to 99)
        </api-ref-item>
        <api-ref-item name="rank" :required="true" type="numeric">
            the rank of restaurant (priority)
        </api-ref-item> 
        <api-ref-item name="start_date" :required="true" type="date">
             the start date of the offer
        </api-ref-item>        
        <api-ref-item name="end_date" :required="true" type="date">
             the expire date of the offer
        </api-ref-item>
    </template>
    </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A new offer  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض بيانات العرض

<api-ref title="get offer data" verb="Get" route="/api/rest-offers/{restaurantOffer}" :response-codes="[200]">
    <template v-slot:description>
جلب  بيانات العرض   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "offer":{},
}
        </pre>
    </template>
</api-ref>

###  حذف العرض

<api-ref title=" delete offer data" verb="delete" route="/api/rest-offers/{restaurantOffer}" :response-codes="[200]">
    <template v-slot:description>
  حذف العرض   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "offer deleted successfully",
}
        </pre>
    </template>
</api-ref>
