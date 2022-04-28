#الارشفة

##ارشفه الطلبات للسائق أو صاحب المطعم

<api-ref title="archive my orders" verb="post" route="/api/archive" :response-codes="[200]">
    <template v-slot:description>
        archive all orders expect last 24 hours
    </template>
    <template v-slot:200>
        <pre>
    {
        'message' => 'your order(s) been archived'
    }
</pre>
</template>
</api-ref>
