<api-ref title="get whats app number" verb="get" route="api/setting/whats-app" :response-codes="[200]">
    <template v-slot:description>
   whats app number  
    </template>
    <template v-slot:200>
        <pre>
{
    "number":string of whats number
}
        </pre>
    </template>
</api-ref>
