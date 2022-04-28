##   تقيم المطعم 
جلب كل  تقيمات الخاصة بالمطعم
<api-ref title="get all ratings of restaurant " verb="get" route="/api/restaurants/rating/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
    جلب كل تقيمات  الخاصة بالمطعم من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
        'ratings' => [array],
}
        </pre>
    </template>
</api-ref>


##  اضافة تقيم للمطعم او تعديل 

<api-ref title="store  rating for restaurant" verb="post" route="/api/restaurants/rating/{restaurant_id}" :response-codes="[200]">
    <template v-slot:description>
اضافة  او تعديل تقيم المطعم
    </template>
    <template v-slot:body>
        <api-ref-item name="rate" :required="true" type="number">
            The rate  between 0-6
        </api-ref-item>
        <api-ref-item name="comment" :required="true" type="text">
            The comment  
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'restaurant rating updated successfully',
}
</pre>
</template>
</api-ref>

##   عرض تقيم الزبون للمطعم 
عرض   تقيم زبون للمطعم
<api-ref title="get all ratings of restaurant " verb="get" route="/api/restaurants/rating/{restaurant_rating}/show" :response-codes="[200]">
    <template v-slot:description>
    عرض تقيم الخاصة بالمطعم من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
        'rating' => {},
}
        </pre>
    </template>
</api-ref>

##   حذف تقيم الزبون للمطعم 
حذف   تقيم زبون للمطعم
<api-ref title="delet ratings of restaurant  " verb="delete" route="/api/restaurants/rating/{restaurant_rating}" :response-codes="[200]">
    <template v-slot:description>
    حذف  تقيم الزبون  للمطعم من قاعدة البيانات
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
  'message' =>'restaurant rating deleted successfully',
}
        </pre>
    </template>
</api-ref>