#أدارة الصفوف و الفصول الدراسية

الصف الدراسي هو المرحلة التى يمر بها الطالب خلال المدرسة كالصف الأول الابتدائي و يتكون كل صف من فصل أو عدد فصول .

<a name="section-1"></a>
##الصفوف

###controller

```php
\App\Http\Controllers\CourseController.php
```

ال policy الخاصة بها

```php
\App\Policies\CoursePolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات  ال database 

```php
\App\Repositories\CourseRepository.php
```

###API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/courses                | POST   |  create   |  add course   | creating and storing a course        
| api/courses                | GET    |  index    | list courses  | view all courses   
| api/courses/{slug}             | GET    |  show     | list courses  | view specific course data  you can add ?withClasses = true in the url to get the classes of the course with it                         
| api/courses/get-classes/{slug} | GET    |  show     | list courses  | view classes of specific course                         
| api/courses/{slug}         | PATCH  |  update   | edit course   | edit specific course data
| api/courses/{slug}         | DELETE |  delete   | delete course | edit specific course

###store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :           |   :    |  :       | :      |
| title        | string  | yes      | 3 : 50 |
| description  | string  | yes      |  |
| is_graduated | boolean | yes      | in case of true then it's graduation course |
| category_id  | numeric | yes      | from category table |

نلاحظ انه يوجد علاقة بين الصفوف الدراسية و الأقسام فى المدراس فالصفوف العربى تختلف عن الدولى كمثال


###العلاقات مع جدول courses


| Table1    | relation   
| :           |   :    |   
| classes        | one to many  


<a name="section-2"></a>
##الفصول


###controller

```php
\App\Http\Controllers\ClassController.php
```

ال policy الخاصة بها

```php
\App\Policies\ClassPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات  ال database

```php
\App\Repositories\ClassRepository.php
```
| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/classes                            | POST   |  create   |  add class   | creating and storing a class        
| api/classes                            | GET    |  index    | list classes | view all classes   
| api/classes/{slug}                     | GET    |  show     | list classes | view specific class data  you can add ?withCourse = true in the url to return the course with the class
| api/classes/{slug}                     | PATCH  |  update   | edit class   | edit specific class data
| api/classes/{slug}                     | DELETE |  delete   | delete class | edit specific class

###store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم


| name    | type    | required | length
| :           |   :    |  :       | :      |
| title        | string  | yes      | 3 : 50 |
| course_id    | numeric  | yes      | from course table |

