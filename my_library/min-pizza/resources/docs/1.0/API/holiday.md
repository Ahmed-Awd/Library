# العطل

## مقدمة

 يمكن اضافة يوم كا عطلة بحيث يكون المطعم مغلق في هذا اليوم من كل سنة

### عرض كل ايام العطل  

<api-ref title="get all  holidays data" verb="Get" route="/api/holiday/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
جلب ايام العطل  
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "holidays":[array],
}
        </pre>
    </template>
</api-ref>

##   اضافة  يوم عطلة جديد

<api-ref title="create new holiday" verb="post" route="/api/holiday" :response-codes="[200]">
    <template v-slot:description>
 ادخال بيانات  السائق  
    </template>
    <template v-slot:body>
        <api-ref-item name="day" :required="true" type="number">
            The day is number between 1-31 
        </api-ref-item>
        <api-ref-item name="month" :required="true" type="number">
            The month is number between 1-12
        </api-ref-item>
        <api-ref-item name="restaurant_id" :required="true" type="number">
            the restaurant id
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
    "message": "A process  created successfully",
}
        </pre>
    </template>
</api-ref>

### عرض يوم العطلة 

<api-ref title="get holiday date" verb="Get" route="/api/holiday/{holiday}" :response-codes="[200]">
    <template v-slot:description>
جلب  يوم العطلة   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "data":{},
}
        </pre>
    </template>
</api-ref>


### تعديل  يوم العطلة

<api-ref title="update  holiday data" verb="put" route="/api/holiday/{holiday}" :response-codes="[200]">
    <template v-slot:description>
 ادخال يوم العطلة    
    </template>
     <template v-slot:body>
        <api-ref-item name="day" :required="true" type="number">
            The day is number between 1-31 
        </api-ref-item>
        <api-ref-item name="month" :required="true" type="number">
            The month is number between 1-12
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
    "message": "A process  updated successfully",
}
        </pre>
    </template>
</api-ref>

###  حذف يوم العطلة

<api-ref title=" delete holiday data" verb="delete" route="/api/holiday/{holiday}" :response-codes="[200]">
    <template v-slot:description>
  حذف يوم العطلة   
 </template>
     <template v-slot:headers>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "message": "A  process  deleted successfully",
}
        </pre>
    </template>
</api-ref>

