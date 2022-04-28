# معايير عامة

- [Routes](#routes)
- [Validation](#validation)
- [Repositories](#repositories)
- [Authorization](#authorization)

<a name="routes"></a>
## Routes

يتم كتابة جميع ال routes الخاصة بالأدمن بانل داخل ملف

```php
routes/web.php
```

و يتم كتابة كافة ال routes الخاصة تطبيقات الهاتف داخل
```php
routes/api.php
```



<a name="validation"></a>
## Validation

يجب استخدام ملفات form request
لكتابة قواعد ال validation


https://laravel.com/docs/8.x/validation#form-request-validation

```php
php artisan make:request StoreItemRequest
```

<a name="repositories"></a>
## Repositories

نقوم بأستخدام repository pattern لتسهيل و تنظيم عملية التعامل بين ال models و ال controllers 

مثال عن استخدام ال repository pattern

https://laravelarticle.com/repository-design-pattern-in-laravel

او مشاهدة هذا الفيديو للتوضيح

https://www.youtube.com/watch?v=93ZhGkFIwbA&ab_channel=Coder%27sTape


<a name="authorization"></a>
## Authorization

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

و يتم ربطها فى app/Providers/AuthServiceProvider.php

```php
    protected $policies = [
        Store::class => StorePolicy::class,
    ];
```

و يتم تنفيذها على ال controller من نوع resource كالاتى

```php

    public function __construct()
    {
        $this->authorizeResource(Branch::class,'branch');
    }
