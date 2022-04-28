## قائمة البلاد

<api-ref title="get all countries" verb="get" route="/api/countries" :response-codes="[200]">
    <template v-slot:description>
    جلب كل البلاد من قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>
{
        'countries' => [array],
}
        </pre>
    </template>
</api-ref>

## قائمة المدن

<api-ref title="get all cities" verb="get" route="/api/cities/{country_id}" :response-codes="[200]">
    <template v-slot:description>
    جلب كل المدن التابعة لبلد قاعدة البيانات
    </template>
    <template v-slot:body>

    </template>
    <template v-slot:200>
        <pre>
{
'cities' => [array],
}
</pre>
</template>
</api-ref>
