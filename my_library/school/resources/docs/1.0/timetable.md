#إدارة جدول الحصص

---

<a name="section-1"></a>
تعتمد المنصة اعتماد كبير علي جدول الحصص حيث يتم ربطها بنظام الفصول الافتراضية والواجبات 

<a name="section-2"></a>
##آليه العمل

يتم في البداية تجهيز جدول الحصص عن طريق انشاء فترات مختلفة وكل فتره لها مواعيد الحصص الخاص بها ويتم هذا في

```php
Http/Controllers/TimingSetController.php
```
ال policy الخاصة ب ال timingset مسجلة فى 
```php
app/Policies/TimingSetPolicy.php
```
<a name="section-3"></a>
ال repository المسؤلة عن كل ما يتعلق بعمليات ال timingset معا ال database فى

```php
app/Repositories/TimingSetRepository.php
```
<a name="section-4"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/timing-sets                | POST   |  create    | add timing set     | creating and storing a timingset        
| api/timing-sets                | GET    |  index     | list timing sets   | view all timingsets 
| api/timing-sets/{slug}           | GET    |  show      | list timing sets   | view specific timingset             
| api/timing-sets/{id}           | PATCH  |  update    | edit timing set    | edit specific timing set data             
| api/timing-sets/{id}           | DELETE |  destroy   | delete timing set  | soft delete specific timing set           
       

<a name="section-5"></a>
##store / update requests

هذا جدول بال parameters المطلوبة في حالة أضافه أو تعديل مستخدم

| name    | type    | required | length
| :                        |   :-    |  :       | :      |
| name                     | string  | yes      | 3 : 40 |
| description                 | string  | yes      | 6 : 25 |
| period_name                | string | yes      | 8 : 15 |
| start_time                    | time  | yes      |        |
| end_time                 | time  | yes      | 6 : 25 |

<a name="section-6"></a>
العلاقات في جدول timingset

| Table1    | relation    | Table2 
| :           |   :    |  :       | :      |
| timingset        | One to many  | timingsetdetails

بعد تجهيز فترات الحصص ومواعيدها يتم تسكين المعلمين في الحصص 
وهذا في 
##controller

```php
Http/Controllers/TimetableController.php
```
ال policy الخاصة ب ال timetable مسجلة فى 

```php
$this->authorize('view timetable');
$this->authorize('update timetable');
$this->authorize('list timetable');
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال timetable معا ال database فى

```php
app/Repositories/TimetableRepository.php
```


<a name="section-7"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/timetable/user/{username}               | GET   |  getUserSchedule    | view timetable     | view timetable for student or staff         
| api/timetable/class/{class}                | GET    |  getClassSchedule     | view timetable   | view timetable for class  
| api/timetable/level/{program}/{track}/{level}        | GET    |  getLevelSchedule      | view timetable   | view timetable for level             
| api/timetable/update/          | PATCH  |  updateSchedule    | update timetable    | update timetable             
| api/timetable/staff_available/    |GET     | isStaffAvailable |  update timetable    | check if staff busy in this session or not            
| api/timetable/staff-report/  | POST   |  staffReport   | list timetable | timetable report for more than one staff             
| api/timetable/classes-report/ | POST   |  classesReport | list timetable |timetable report for more than one class       

<a name="section-8"></a>
##store / update requests

هذا جدول بال parameters المطلوبة في حالة أضافه أو تعديل مستخدم

| name    | type    | required | length
| :                        |   :-    |  :       | :      |
| semester_id                     | numeric  | yes      | reference to academic_semesters table |
| class_id                 | numeric  | no      |reference to classes table |
| day                | numeric | yes      | |
| timingset_details_id   | numeric  | yes      |     reference to timingsetdetails table   |
| user_id                 | string  | yes      | reference to users table|
| subject_id              | numeric | yes      | reference to subjects table |
| branch_id                | numeric | yes      | reference to branch table |
| vclass_hidden           | boolean  | yes      |
