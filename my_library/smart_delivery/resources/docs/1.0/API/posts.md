# الصفحات

## مقدمة

عبارة صفحات بأكثر من لغة   

## جلب كل الصفحات بالنسبة للغة 

يكمن جلب كل الصفحات للغة واحدة 
### جلب كل الصفحات
<api-ref title="get all posts" verb="get" route="/api/posts" :response-codes="[200]">
    <template v-slot:description>
   الصفحات  
    </template>
     <template v-slot:headers>
        <api-ref-item name="accept-language" :required="false" type="string" example="application/json">
            accept language (ar, en ,tr)
        </api-ref-item>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "posts": [
    {
      "id": 1,
      "created_at": "2021-07-14T12:54:30.000000Z",
      "updated_at": "2021-07-15T10:03:18.000000Z",
      "post_id": 15,
      "language": "ar",
      "title": "عناون",
      "content": "<p>محتوى</p>"
    },
    {
      "id": 10,
      "created_at": "2021-07-14T15:48:39.000000Z",
      "updated_at": null,
      "post_id": 18,
      "language": "ar",
      "title": "عناون",
      "content": "محتوى"
    }
    ]
}
        </pre>
    </template>
    
</api-ref>

### جلب  صفحة 
<api-ref title="get one post" verb="get" route="/api/posts/{post_id}" :response-codes="[200]">
    <template v-slot:description>
   الصفحة  
    </template>
     <template v-slot:headers>
        <api-ref-item name="accept-language" :required="false" type="string" example="application/json">
            accept language (ar, en ,tr)
        </api-ref-item>
        <api-ref-item name="Bearer Token" :required="true" type="string" example="application/json">
            Accept json responses
        </api-ref-item>
    </template>
    <template v-slot:200>
        <pre>
{
    "post": {
    "id": 1,
    "created_at": "2021-07-14T12:54:30.000000Z",
    "updated_at": "2021-07-15T10:03:18.000000Z",
    "deleted_at": null,
    "post_id": 15,
    "language": "ar",
    "title": "عناون",
    "content": "<p>محتوى</p>"
  }
}
        </pre>
    </template>
</api-ref>
