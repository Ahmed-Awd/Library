#أدارة الدروس و المحتوى التعليمي

هذا الجزء من النظام مسؤول عن الدروس الدراسية و ما يحتويها من محتوى تعليمى 
<a name="section-1"></a>

##Topics
الدروس تنقسم الى وحدات و الى دروس داخل الوحدات و جميعهم مخزنين داخل جدول Topics 

###controller
```php
\App\Http\Controllers\TopicController
```

ال policy الخاصة ب ال topics مسجلة فى

```php
\App\Policies\TopicPolicy
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال  database فى

```php
\App\Repositories\TopicRepository
```

###API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/topics                         | POST   |  create          |  add topic   | creating and storing a topic        
| api/topics                         | GET    |  index           | list topics  | view all topics you can add with withSubTopics = true to get the subtopics with main topics 
| api/topics/{slug}                  | GET    |  show            | view topics  | view specific topic data             
| api/topics/{slug}                  | PATCH  |  update          | edit topic   | edit specific topic data
| api/topics/{slug}                  | DELETE |  delete          | delete topics  | delete specific topic
| api/topics/get-subtopics/{slug}    | GET    |  getSubTopics    | view topics  | get subtopics of specific topic
| api/topics/get-parent/{slug}       | GET    |  getParentTopic  | view topics  | get parent topic of specific topic
| api/topics/get-content/{slug}      | GET    |  getTopicContent | view topics  | get lms content of topic


###store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :            |   :    |  :       | :      |
| name         | string   | yes      | 3 : 200 |
| description  | string   | yes      |  |
| multi_branch | boolean  | yes      | if true then the topic is multi branched |
| parent_id    | numeric  | no       | the id of the parent topic |
| subject_id   | numeric  | yes      | the id of the subject |

###relations

| Table1    | relation    | Table 2
| :           |   :    |  :       | :      |
| topics        | one to many  | topics
| topics        | one to many  | lms_contents
| subjects      | one to many  | topics


##lms content

المحتوى التعليمي هو محتوى الدرس من كتابة أو فيديو أو صور أو ملفات


###controller
```php
\App\Http\Controllers\ContentController
```

ال policy الخاصة ب ال content مسجلة فى

```php
\App\Policies\ContentPolicy
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال  database فى

```php
\App\Repositories\ContentRepository
```

###API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/contents                         | POST   |  create          |  add content     | creating and storing a content        
| api/contents                         | GET    |  index           | list contents    | view all contents
| api/contents/{slug}                  | GET    |  show            | view contents    | view specific content data             
| api/contents/{slug}                  | PATCH  |  update          | edit content     | edit specific content data
| api/contents/{slug}                  | DELETE |  delete          | delete content   | delete specific content



###store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :            |   :    |  :       | :      |
| title        | string          |  yes              | 3 : 100 |
| description  | string          |  yes              |  |
| multi_branch | boolean         |  yes             | if true then the topic is multi branched |
| topic_id     | numeric         |  no              | the id of the topic |
| course_id    | numeric         |  no              | the id of the course |
| image        | file(image)     |  yes              | image of the content cover |
| content_type | string          |  yes              | type of content enum (file,video,audio,url,video_url,iframe,meeting_url,image_file) |
| file_path    | string or file  |  yes            | the content it can be file or url |
| is_published | boolean         |  yes             | determine if the content is published or not |


###relations

| Table1    | relation    | Table 2
| :           |   :    |  :       | :      |
| topics        | one to many  | lms_contents
| courses       | one to many  | lms_contents


##Lessons
يمكن لكل معلم تحديد الدروس التي أنتها منها على مدار الترم عن طريق ال lessons

###controller
```php
\App\Http\Controllers\LessonController
```

###API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/lessons/check/{topic}/{class?}   | GET |  check |  check lessons   | check and uncheck a topic with class if it's school system        
| api/lessons/{subject}/{class?}       | GET |  get   |  | view all lessons of subject with class if school system


