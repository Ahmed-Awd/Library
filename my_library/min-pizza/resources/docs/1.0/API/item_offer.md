#عروض القائمة


### عرض بيانات كل العروض

<api-ref title="get all  items offers" verb="Get" route="/api/item-offers" :response-codes="[200]">
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

<api-ref title="start an offer" verb="post" route="/api/item-offers" :response-codes="[200]">
    <template v-slot:description>
 إدخال بيانات  العرض  
    </template>
    <template v-slot:body>
        <api-ref-item name="item_id" :required="true" type="numeric">
            the item id
        </api-ref-item>
        <api-ref-item name="new_price" :required="true" type="numeric">
            the new price of the item
        </api-ref-item>
        <api-ref-item name="rank" :required="true" type="numeric">
            the rank of the item (priority)
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

<api-ref title="get offer data" verb="Get" route="/api/item-offers/{itemOffer}" :response-codes="[200]">
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

<api-ref title=" delete offer data" verb="delete" route="/api/item-offers/{itemOffer}" :response-codes="[200]">
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
