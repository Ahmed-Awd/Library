#ادارة المواد الدراسية

---


<a name="section-1"></a>
##controller

```php
Http/Controllers/SubjectController.php
```
ال policy الخاصة ب ال subjects مسجلة فى

```php
app/Policies/SubjectPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال subjects مع ال database فى

```php
app/Repositories/SubjectRepository.php
```

<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/subjects                | POST   |  create   |  add subject | creating and storing a subject        
| api/subjects                | GET    |  index    | list subjects | view all subjects   
| api/subjects/{id}           | GET    |  show     | list subjects   | view specific subject data             
| api/subjects/{id}           | PATCH  |  update   | edit subject    | edit specific subject data
| api/subjects/{id}           | DELETE  |  delete   | delete subject    | edit specific subject
| api/subjects/config/{subject_id}          | GET  |  showSubjectConfig   |   | show subject configuration
| api/subjects/config/{subject_id}          | POST  |  updateSubjectConfig   |   | update subject configuration

<a name="section-3"></a>
##store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :           |   :    |  :       | :      |
| title        | string  | yes      | 3 : 225 |
| slug        | string  | yes      | 3 : 225 |
| is_lab        | boolean  | yes      
| category_id        | bigint  | yes      

<a name="section-4"></a>
##العلاقات في جدول subjects
بما ان المنصة تعمل علي نظامين وهما المدارس والجامعات فهناك جداول نحتاجها في الجامعات وجداول نحتاجها في المدارس وجداول مشتركة

##العلاقات في المدارس
| Table1    | relation    | Table2
| :           |   :    |  :       | :      |
| subjects        | One to Many  | course_subjects

##العلاقات في الجامعات

| Table1    | relation    | Table2
| :           |   :    |  :       | :      |
| subjects        | One to One  | university_subject_configs
| subjects        | One to Many  | university_subjects

##العلاقات المشتركة

| Table1    | relation    | Table2
| :           |   :    |  :       | :      |
| subjects        | One to Many  | staff_subjects
| subjects        | One to Many  | student_subjects
