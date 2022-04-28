#أدارة البرامج الأكاديمية

البرامج الأكاديمية هى ما يلتحق به الطالب من برنامَج لحصول على شهادة معينة مثل الماجستير و الدكتوراه و البكالريوس
<a name="section-1"></a>

##controller

```php
Http/Controllers/ProgramController.php
```

ال policy الخاصة ب ال programs مسجلة فى

```php
app/Policies/ProgramPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال programs مع ال database فى

```php
app/Repositories/ProgramRepository.php
```


<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/programs                | POST   |  create   |  add program   | creating and storing a program        
| api/programs                | GET    |  index    | list programs  | view all programs   
| api/programs/{slug}         | GET    |  show     | list programs  | view specific program data             
| api/programs/{slug}         | PATCH  |  update   | edit program   | edit specific program data
| api/programs/{slug}         | DELETE |  delete   | delete program | edit specific program

<a name="section-3"></a>
##store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :           |   :    |  :       | :      |
| name        | string  | yes      | 4 : 25 |

<a name="section-3"></a>
العلاقات مع جدول university_programs

| Table1    | relation    | pivot table
| :           |   :    |  :       | :      |
| subjects        | many to many  | university_subjects
| levels          | many to many  | university_track_levels

