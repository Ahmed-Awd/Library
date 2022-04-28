#ادارة الأقسام

---
الأفسام الدراسية تشمل (عربي - دولي -لغات) حسب اقسام المدرسة واحتياجتها 
حيث تحتاج بعض المدارس ان يكون مسؤلون ومعلمين وطلاب وبيانات كل قسم منفردين

<a name="section-1"></a>
##controller

```php
Http/Controllers/CategoryController.php
```
ال policy الخاصة ب ال categories مسجلة فى

```php
app/Policies/CategoryPolicy.php
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال categories مع ال database فى

```php
app/Repositories/CategoryRepository.php
```
<a name="section-3"></a>
##global scope and models

لتطبيق مبدأ فصل الاقسام في المنصة نستخدم laravel global scope

https://laravel.com/docs/8.x/eloquent#global-scopes

ال global scope الخاص ب القسم موجود فى

```php
app/Scopes/CategoryScope.php
```

```php
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {
            $category_id = auth()->user()->category_id;
            $builder->where('category_id', '=', $category_id);
        }
    }
```

ثم نقوم بتطبيق ال global scope فى اى model كالتلى

```php

    protected static function booted()
    {
        static::addGlobalScope(new CategoryScope);
    }

```

<a name="section-4"></a>

<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/categories                | POST   |  create   |  add category | creating and storing a category        
| api/categories                | GET    |  index    | list categories | view all categories   
| api/categories/{id}           | GET    |  show     | list categories   | view specific category data             
| api/categories/{id}           | PATCH  |  update   | edit category    | edit specific category data
| api/categories/{id}           | DELETE  |  delete   | delete category    | edit specific category

<a name="section-3"></a>
##store / update requests

هذا جدول بال parameters المطلوبة فى حالة اضافة او تعديل مستخدم

| name    | type    | required | length
| :           |   :    |  :       | :      |
| name        | string  | yes      | 3 : 225 |

<a name="section-3"></a>
العلاقات في جدول categories

| Table1    | relation    | Table2
| :           |   :    |  :       | :      |
| categories        | One to many  | users
