#ادارة السنوات الدراسية

---
يتم تخزين معظم بيانات المدرسة علي مستوي السنة الدراسية
<a name="section-1"></a>

 عند اضافة سنة دراسية يلزم اضافة الفصول الدراسية المرتبطة بها (مثال:الفصل الدراسي الأول)


<a name="section-1"></a>
##controller

```php
Http/Controllers/AcademicYearController.php
```
ال policy الخاصة ب ال academics مسجلة فى

```php
app/Policies/AcademicYearPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال academics معا ال database فى

```php
app/Repositories/AcademicYearRepository.php
```


<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/academics                | POST   |  create   |  add academic year   | creating and storing a year with their semesters         
| api/academics                | GET    |  index    | list years   | view all academic years   
| api/academics/{id}           | GET    |  show     | list years   | view specific year data             
| api/academics/{id}           | PATCH  |  update   | edit academic year    | edit specific year data             

<a name="section-3"></a>
##store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :           |   :    |  :       | :      |
| academic_year_title        | string  | yes      | 3 : 225 |
| academic_start_date    | date  | yes    |  
| academic_end_date   | date | yes      
| show_in_list       | boolean  | yes      |        |
| sem_start_date    | datetime  | yes      |
| sem_end_date | datetime | yes      |

<a name="section-3"></a>
العلاقات في جدول academics

| Table1    | relation    | Table2 
| :           |   :    |  :       | :      |
| academics        | One to many  | academic_semesters
