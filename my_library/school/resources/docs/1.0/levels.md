#أدارة المستويات الأكاديمية

المستويات الأكاديمية هى المستويات التى يدرسها الطالب لكى يتم مرحلة نيل الشهادة المطلوبة ف لكل برنامج اكاديمى عدد مستويات

<a name="section-1"></a>

##controller

```php
Http/Controllers/LevelController.php
```

ال policy الخاصة ب ال levels مسجلة فى

```php
App/Policies/LevelPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال Levels مع ال database فى

```php
app/Repositories/LevelRepository.php
```


<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/levels                | POST   |  create   |  add level   | creating and storing a level        
| api/levels                | GET    |  index    | list levels  | view all levels   
| api/levels/{slug}         | GET    |  show     | list levels  | view specific level data             
| api/levels/{slug}         | PATCH  |  update   | edit level   | edit specific level data
| api/levels/{slug}         | DELETE |  delete   | delete level | edit specific level

<a name="section-3"></a>
##store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :           |   :    |  :       | :      |
| name        | string  | yes      | 3 : 25 |

<a name="section-3"></a>
##العلاقات مع جدول university_levels


| Table1    | relation    | pivot table
| :           |   :    |  :       | :      |
| subjects        | many to many  | university_subjects
| tracks          | many to many  | university_track_levels


<a name="section-4"></a>
##تعيين المستويات للمسارات

يمكنك تعيين المستويات لكل من البرامج و المسارات فى ال route التالى
 و يستخدم middleware من نوع gate can:assign-level
```php
api/levels/assign/{program}/{track}    (post request)
```
مع اعتبار ان ال program و ال track هما slugs و معا الdata التالية

| name    | type    | required | length
| :           |   :    |  :       | :      |
| levels        | array  | yes      | array of levels ids |

و يمكنك عرض المستويات التى تم تعينها عن طريق

```php
api/levels/assigned/{program}/{track}    (get request)
```
