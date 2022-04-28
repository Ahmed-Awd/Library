# معايير عامة

- [Routes](#routes)
- [Validation](#validation)
- [Repositories](#repositories)
- [Authorization](#authorization)
- [Prices](#prices)

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

```

<a name="prices"></a>
## Prices

تخزين الأسعار في قاعدة البيانات يكون باستخدام قيمة Integer وليس Float، السبب هو لتجنب قلة الدقة التي تنتج عن العمليات الحسابية على الـ Float.
فيتم تخزين جميع الأسعار بعد تحويلها الى اصغر عملة ممكنة بالضرب بـ 100، فمثلا القيمة 3.25$ تخزن على أنها 325 cent لكن عند عرضها للمستخدم
تعرض بالشكل الأصلي 3.25 عن طريق قسمة القيمة المخزنة على 100
كذلك عند ارسال اي عملة عن طريق أي Form يتم ارسالها على شكل قيمة Float وتُحول قبل التخزين إلى قيمة Integer.

القاعدة العامة للتعامل مع العملات كالآتي:

| المكان | الإجراء |
| --- | --- |
| عند ارسال قيمة من التطبيق أو الموقع إلى الباك اند | تتم جميع العمليات الحسابية اللازمة ثم يتم ضرب النتيجة بـ 100 قبل التخزين |
| عند عرض قيمة من الباك اند الى الموقع | تُعرض بعد قسمة القيمة المخزنة على 100 |
| عند العرض في الـ API | يتم إرسال القيمة بشكلها المخزن الأصلي في حال احتاج مطور التطبيقات لعمل أي عمليات حسابية في التطبيق، لكن عند العرض يقوم بالقسمة على 100 قبل عرضها |
