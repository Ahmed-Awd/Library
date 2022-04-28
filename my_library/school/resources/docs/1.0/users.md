#أدارة المستخدمين

---

<a name="section-1"></a>
##أنواع المستخدمين

النظام يحتوى على انواع مختلفة من المستخدمين بعضهم اساسى و الاخر حسب ما تريد المدرسة لذلك نستخدم spatie/laravel-permission التي تتيح لنا المرونة فى ذلك .

المستخدمين الأساسين هم (owner , admin , student , staff)


المستخدمين الغير اساسين هم (parent,librarian,assistant librarian,educational supervisor,secondary parent,student guide,level agent,school stage manager)


<a name="section-2"></a>
##controller

```php
Http/Controllers/UserController.php
```
ال policy الخاصة ب ال users مسجلة فى 

```php
app/Policies/UserPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال user معا ال database فى

```php
app/Repositories/UserRepository.php
```


<a name="section-3"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/users                | POST   |  create    | add user     | creating and storing a user         
| api/users                | GET    |  index     | list users   | view all users or a type of users   
| api/users/{id}           | GET    |  show      | list users   | view specific user data             
| api/users/{id}           | PATCH  |  update    | edit user    | edit specific user data             
| api/users/{id}           | DELETE |  destroy   | delete user  | soft delete specific user           
| api/users/suspend/{id}   | POST   |  suspend   | suspend user | suspend a specific user             
| api/users/unsuspend/{id} | POST   |  unsuspend | suspend user | un suspend a specific user          

<a name="section-4"></a>
##store / update requests

هذا جدول بال parameters المطلوبة في حالة أضافه أو تعديل مستخدم

| name    | type    | required | length
| :                        |   :-    |  :       | :      |
| name                     | string  | yes      | 3 : 40 |
| username                 | string  | yes      | 6 : 25 |
| id_number                | numeric | yes      | 8 : 15 |
| email                    | string  | yes      |        |
| password                 | string  | yes      | 6 : 25 |
| password_confirmation    | string  | yes      | same as password |
| category_id              | numeric | yes      | reference to category table |
| branch_id                | numeric | yes      | reference to branch table |
| role                     | string  | yes      | reference to roles table |
| language_id              | numeric | yes      | reference to language table |
| phone                    | string  | no       |  |
| nationality              | string  | no       |  |
| address                  | string  | no       |  |
| image                    | file    | no       | jpg,jpeg,png |
