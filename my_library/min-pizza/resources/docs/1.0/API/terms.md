##السياسات العامة للموقع

###about us

<api-ref title="about us" verb="get" route="/api/about-us" :response-codes="[200]">
    <template v-slot:description>
        about us info
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'about_us' => {object},
}
</pre>
</template>
</api-ref>

###update about us

<api-ref title="about us" verb="post" route="/api/about-us" :response-codes="[200]">
    <template v-slot:description>
        about us info
    </template>
    <template v-slot:body>
        <api-ref-item name="english_value" :required="true" type="string">
            the english value of about us
        </api-ref-item> 
        <api-ref-item name="arabic_value" :required="true" type="string">
           the arabic value of about us
        </api-ref-item>      
        <api-ref-item name="swedish_value" :required="true" type="string">
            the swedish value of about us
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' => "information updated successfully",
}
</pre>
</template>
</api-ref>


###terms and conditions

<api-ref title="terms and conditions" verb="get" route="/api/terms-and-conditions" :response-codes="[200]">
    <template v-slot:description>
        terms and conditions
    </template>
    <template v-slot:body>
    </template>
    <template v-slot:200>
        <pre>
{
'terms' => {object},
}
</pre>
</template>
</api-ref>

###update terms and conditions

<api-ref title="terms and conditions" verb="post" route="/api/terms-and-conditions" :response-codes="[200]">
    <template v-slot:description>
        terms and conditions
    </template>
    <template v-slot:body>
        <api-ref-item name="english_value" :required="true" type="string">
            the english value of terms
        </api-ref-item> 
        <api-ref-item name="arabic_value" :required="true" type="string">
           the arabic value of terms
        </api-ref-item>      
        <api-ref-item name="swedish_value" :required="true" type="string">
            the swedish value of terms
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
'message' => "information updated successfully",
}
</pre>
</template>
</api-ref>
