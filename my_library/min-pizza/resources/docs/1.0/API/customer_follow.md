##    متابعة العميل للمطاعم  
عرض المطاعم التي تم متابعتها من قبل العميل
<api-ref title="get all restaurants that have been followed by the customer " verb="get" route="/api/my-followed-restaurants" :response-codes="[200]">
    <template v-slot:description>
   
عرض المطاعم التي تم متابعتها من قبل العميل وتظهر للعميل نفسه
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
        'myFollows' => [array],
}
        </pre>
    </template>
</api-ref>


##  اضافة متابعة لمطعم من العميل أو الغاؤها

<api-ref title="start following or unfollow restaurant" verb="post" route="/api/restaurants/follow/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
اضافة او الغاء متابعة للمطعم
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
'message' =>'restaurant follow updated successfully',
}
</pre>
</template>
</api-ref>

