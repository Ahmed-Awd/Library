#أدارة المسارات الأكاديمية

المسارات الأكاديمية هيا التخصصات الأكاديمية مثل الكيمياء و الفيزياء و علوم الحساب فيقوم كل طالب باختيار المسار الخاص به و على أساسه يتحدد اشياء كثيرة ك المواد التي يقوم بدراسيتها .
<a name="section-1"></a>

##controller

```php
Http/Controllers/TrackController.php
```

ال policy الخاصة ب ال tracks مسجلة فى

```php
app/Policies/TrackPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال tracks مع ال database فى

```php
app/Repositories/TrackRepository.php
```


<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/tracks                | POST   |  create   |  add track   | creating and storing a track        
| api/tracks                | GET    |  index    | list tracks  | view all tracks   
| api/tracks/{slug}         | GET    |  show     | list tracks  | view specific track data             
| api/tracks/{slug}         | PATCH  |  update   | edit track   | edit specific track data
| api/tracks/{slug}         | DELETE |  delete   | delete track | edit specific track

<a name="section-3"></a>
##store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :           |   :    |  :       | :      |
| name        | string  | yes      | 2 : 25 |

<a name="section-3"></a>
العلاقات مع جدول university_tracks

| Table1    | relation    | pivot table
| :           |   :    |  :       | :      |
| subjects        | many to many  | university_subjects
| levels          | many to many  | university_track_levels
<a name="section-4"></a>
##تعيين المواد للمسارات

يمكنك تعيين المواد لكل من البرامج و المسارات و المستويات فى ال route التالى

```php
api/tracks/assign-subjects/{track}/{program}/{level?} (post request)
```
مع اعتبار ان ال program و ال  track  و level هما slugs و معا الdata التالية

| name    | type    | required | length
| :           |   :    |  :       | :      |
| subjects        | array  | yes      | array of subjects ids |
| is_mandatory        | boolean  | yes      | determine if the subjects is mandatory or optional |
| semester        | numeric  | yes      | id of a semester  |

و يمكنك عرض الواد التى تم تعينها عن طريق

```php
api/tracks/assigned-subjects/{track}/{program}/{semester}/{level?} (get request)
```
