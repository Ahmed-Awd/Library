#أدارة الفروع

---

<a name="section-1"></a>
##معلومات عامة

 ادارة الفروع من اهم المزايا فى منصة علم حيث انها تقوم بتقسيم الطلاب و المعلمين الى اقسام مدرسة بنين و بنات على مستوى المستخدمين و ايضا الامتحانات و الفصول و الواجبات و مزايا اخرى فى النظام.

<a name="section-2"></a>
##controller

```php
Http/Controllers/BranchController.php
```
ال policy الخاصة ب ال branch مسجلة فى

```php
app/Policies/BranchPolicy.php
```


ال repository المسؤلة عن كل ما يتعلق بعمليات ال branch معا ال database فى

```php
app/Repositories/BranchRepository.php
```

<a name="section-3"></a>
##global scope and models

لتطبيق مبدأ فصل كل من البنين و البنات او ال branching على ال model نستخدم laravel global scope

https://laravel.com/docs/8.x/eloquent#global-scopes

ال global scope الخاص ب البرانش موجود فى

```php
app/Scopes/BranchScope.php
```

```php
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {
            $id = Auth::user()->branch_id;
            $builder->where('branch_id', '=', $id);
        }
    }
```

ثم نقوم بتطبيق ال global scope فى اى model كالتلى

```php

    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }

```

<a name="section-4"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/branches                | POST   |  create    | add branch     | creating and storing a branch         
| api/branches                | GET    |  index     | list branch    | view all branches 
| api/branches/{id}           | GET    |  show      | list branch    | view specific branch data             
| api/branches/{id}           | PATCH  |  update    | edit branch    | edit specific branch data             
| api/branches/{id}           | DELETE |  destroy   | delete branch  | soft delete specific branch


<a name="section-5"></a>
##store / update requests

هذا جدول بال parameters المطلوبة في حالة أضافه أو تعديل فرع

| name    | type    | required | length
| :           |   :-    |  :       | :      |
| name        | string  | yes      | 4 : 40 |
