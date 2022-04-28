##   اعدادات المطعم 
جلب بيانات الاعدادات الخاصة بالمطعم
<api-ref title="get all setting of restaurant " verb="get" route="/api//restaurants/setting/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
    جلب كل اعدادت  الخاصة بالمطعم من قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>
{
        'setting' => {object},
}
        </pre>
    </template>
</api-ref>


##  اضافة اعدادات المطعم او تعديل 

<api-ref title="store all setting of restaurant" verb="post" route="/api/restaurants/setting/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
اضافة  او تعديل اعدادت المطعم
    </template>
    <template v-slot:body>
        <api-ref-item name="taken_percentage_from_delivery" :required="true" type="number">
            The taken percentage from delivery
        </api-ref-item>
        <api-ref-item name="taken_percentage_from_takeaway" :required="true" type="number">
            The taken percentage from takeaway 
        </api-ref-item>
        <api-ref-item name="max_delivery_distance" :required="true" type="number">
            The max delivery distance 
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'restaurant settings updated successfully',
}
</pre>
</template>
</api-ref>

##   تعديل حالة المطعم

<api-ref title="update status setting of restaurant" verb="post" route="/api/restaurants/status/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
تعديل حالة المطعم   
    </template>
    <template v-slot:body>
        <api-ref-item name="status_id" :required="true" type="number">
            The status number 
        </api-ref-item>
         <api-ref-item name="closing_time" :required="false" type="number">
         Closing time is the number of minutes the restaurant is closed
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'restaurant status updated successfully',
}
</pre>
</template>
</api-ref>

