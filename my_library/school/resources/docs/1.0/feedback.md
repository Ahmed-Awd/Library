#إدارة الآراء و المقترحات

 هذا القسم مسؤول عن إدارة الآراء والمقترحات التي ترسل من جميع أنواع المستخدمين إلى الادمن

<a name="section-1"></a>

##controller

```php
\App\Http\Controllers\FeedbackController
```

ال policy الخاصة ب ال feedbacks مسجلة فى

```php
\App\Policies\FeedbackPolicy
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال feedbacks مع ال database فى

```php
\App\Repositories\FeedbackRepository
```


<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/feedbacks                | POST   |  create      | add feedback    | creating and storing a feedback        
| api/feedbacks                | GET    |  index       | list feedbacks  | view all feedbacks   
| api/feedbacks/{slug}         | GET    |  show        | view feedbacks  | view specific feedback data             
| api/feedbacks/{slug}         | PATCH  |  update      | edit feedback   | edit specific feedback data
| api/feedbacks/{slug}         | DELETE |  delete      | delete feedback | edit specific feedback
| api/remove-file/{slug}       | DELETE |  removeFile  | edit feedback   | delete  feedback specific file

<a name="section-3"></a>
##store / update requests

هذا جدول بال parameters المطلوبة فى حالة الإضافة او التعديل 

| name    | type    | required | length
| :           |   :    |  :       | :      |
| title        | string  | yes      | 3 : 50 |
| subject      | string  | yes      | 5 : 100 |
| description  | string  | yes      | text |
| files        | array of files  | yes      | can be empty in case of no files |

<a name="section-3"></a>
##العلاقات مع جدول feedback_files

| Table1    | relation    | pivot table
| :           |   :    |  :       | :      |
| feedback_files        | one to many  | 

