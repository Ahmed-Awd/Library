##  اوقات الدوام

<api-ref title="get all worktime of restaurant " verb="get" route="/api/restaurants/worktime/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
    جلب كل اوقات الدوام الخاصة بالمطعم من قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>
{
        'worktimes' => [array],
}
        </pre>
    </template>
</api-ref>

##   الايام

<api-ref title="get all days  " verb="get" route="/api/days" :response-codes="[200]">
    <template v-slot:description>
    جلب كل  الايام من قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>
{
        'days' => [array],
}
        </pre>
    </template>
</api-ref>

##  اضافة اوقات الدوام او تعديل 
يتم عن طريق رفع كل اوقات الدوام 
<api-ref title="store all worktime of restaurant" verb="post" route="/api/restaurants/worktime/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
اضافة اوقات الدوام
    </template>
    <template v-slot:body>
      <api-ref-item name="Worktimes" :required="true" type="array">
          The array Worktimes contain day_id (int) ,open_from (time), open_to (time) ,takeaway (boolean),delivery (boolean) and status (boolean)
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'Worktimes created successfully',
}
</pre>
</template>
</api-ref>

