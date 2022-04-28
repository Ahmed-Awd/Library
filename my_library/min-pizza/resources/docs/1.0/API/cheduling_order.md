##   الطلبات المجدولة
العمليات على الطلبات الخاصة المجدولة 

##  اضافة طلب  جديد
عند انشاء الطلبية في حال كانت الوجبة لديه option tamplate 
تحتاج ترسل بيانات option primary

| # | variable   | value |
| : |   :-   |  :  |
| 1 | primary_option_value_id | number  |
| 2 | primary_option_quantity   | number  |
وايضا ترسل بالoption secandary وهذا راح يكون اكثر من واحد تحت مسمى template_selected_options 

| # | variable   | value |
| : |   :-   |  :  |
| 1 | option_secondary_id | number  |
| 2 | quantity   | number  |

اما في حال كانت الوجبة لديه options راح ترسل الoption تحتى مسمى 
selected_options

| # | variable   | value |
| : |   :-   |  :  |
| 1 | option_vlaue_id | number  |
| 2 | quantity   | number  |

<api-ref title="add new order " verb="post" route="api/orders" :response-codes="[200]">
    <template v-slot:description>
اضافة طلب جديد
    </template>
    <template v-slot:body>
      <api-ref-item name="address_id" :required="true" type="number">
            address id is id address
        </api-ref-item>
        <api-ref-item name="restaurant_id" :required="true" type="number">
            restaurant id is id restaurant 
        </api-ref-item>
        <api-ref-item name="scheduling_date" :required="false" type="date">
            scheduling date id date order format YYYY-MM-DD
        </api-ref-item>
        <api-ref-item name="scheduling_time" :required="false" type="time">
            scheduling time is time order format HH:MM:SS
        </api-ref-item>
        <api-ref-item name="service_info_type" :required="true" type="boolean">
            service info type is 0=> takeaway 1=> delivery
        </api-ref-item>
         <api-ref-item name="order_items" :required="true" type="array">
             order_items is array of content 
             item_id => item id is id item 
             quantity => quantity is quantity of item 
             note => note is note about item 
             if(option is template )
             primary_option_value_id => is id of primary option value
             primary_option_quantity => is quantity of primary option value
             template_selected_options =>  template_selected_options is array of content
             [
             option_secondary_id => is id of option secondary of template 
             quantity =>is quantity of option secondary of template
             ]
            else 
            selected_options =>  selected_options is array of content
            [
            option_vlaue_id=> option value id is id of option value
            quantity =>  quantity is quantity of option value
            ]
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' =>'order is created successfully',
}
</pre>
</template>
</api-ref>


