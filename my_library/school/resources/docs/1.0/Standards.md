# معايير عامة

---



<a name="section-1"></a>
##routes

يتم كتابة جميع ال routes الخاصة بنظام المدارس داخل ملف 

```php
routes/tenant.php
```

و يتم كتابة كافة ال routes الخاصة بلوحة التحكم الرئيسية داخل
```php
routes/api.php
```

يجب استخدام صيغة apiResources
في حال كان التعامل مع resource

https://laravel.com/docs/8.x/controllers#api-resource-routes

```php
php artisan make:controller TestController --api
```



<a name="section-2"></a>
##validation

يجب استخدام ملفات form request
لكتابة قواعد ال validation


https://laravel.com/docs/8.x/validation#form-request-validation

```php
php artisan make:request StoreItemRequest
```

<a name="section-3"></a>
##repository pattern

نقوم بأستخدام repository pattern لتسهيل و تنظيم عملية التعامل بين ال models و ال controllers نظرا لضخامة ال project

مثال عن استخدام ال repository pattern

https://laravelarticle.com/repository-design-pattern-in-laravel

او مشاهدة هذا الفيديو للتوضيح 

https://www.youtube.com/watch?v=93ZhGkFIwbA&ab_channel=Coder%27sTape


<a name="section-4"></a>
##Authorization

نقوم بأستخدام api token لعملية الAuthorization عن طريق laravel/sanctum 

https://laravel.com/docs/8.x/sanctum

و نستخدم ال policy الخاصة ب laravel معا ال permissions عن طريق spatie/laravel-permission

https://spatie.be/docs/laravel-permission/v4/basic-usage/basic-usage

حيث نقوم بعمل ملف policy خاص بكل خاصية فى النظام


```php
php artisan make:policy UserPolicy
```

فى حالة ربطه ب model
```php
php artisan make:policy UserPolicy --model=User
```

كل ما يخص ال policies فى الرابط التالى
https://laravel.com/docs/8.x/authorization

مثال عن ال policy المستخجمة حاليا فى ال project

```php
class BranchPolicy
{
use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list branch');
    }


    public function view(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('list branch');
    }


    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add branch');
    }


    public function update(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('edit branch');
    }


    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('delete branch');
    }


    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('delete branches');
    }
}
```

و يتم ربطها فى app/Providers/AuthServiceProvider.php

```php
    protected $policies = [
        Branch::class => BranchPolicy::class,
    ];
```

و يتم تنفزها على ال controller من نوع resource كالاتى

```php

    public function __construct()
    {
        $this->authorizeResource(Branch::class,'branch');
    }

```
