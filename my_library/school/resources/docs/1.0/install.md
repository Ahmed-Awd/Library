#خطوات التنزيل

---



<a name="section-1"></a>
##تحمليل المشروع

يتم تحميل المشروع من خلال bitbucket

https://bitbucket.org/smartlivesws/alam-api/src/master/


<a name="section-2"></a>
##متطلبات تشغيل المشروع

1 - nginx or apache

2 - PHP v8

3 - mysql

4 - composer v2.x

5 - docker (recommended) 

<a name="section-3"></a>
##تشغيل المشروع

بعد تجهيز متطلبات المشروع نقوم بتحميل جميع المكتبات الخاصة بالمشروع من خلال

```php
composer install
```
ومن ثم تعديل ملف env الخاص بالمشروع

<a name="section-4"></a>
##تجيز قاعدة البيانات

هذا المشروع يعمل بخاصية ال multi tenant و ذلك لارضاء كافة المدارس التى تعمل بشكل منفرد ولهذا نقوم بأستخدام tenancy for laravel

https://tenancyforlaravel.com/docs/v3/quickstart/

اولا نقوم بعملية ال migration

```php
php artisan migrate
```
يتم انشاء tenant جديد عن طريق tinker
```php
php artisan tinker

$tenant1 = Tenant::create(['id' => 'alanjal']);
$tenant1->domains()->create(['domain' => 'alanjal.localhost]);
```

ثم نقوم بعملية ال migration لل tenants

```php
php artisan tenants:migrate
```

ثم نقوم باضافة ال data داخل ال tenants عن طريق seeder

```php
php artisan tenants:seed
```
ملاحظة : أي ملف migratrion جديد يضاف داخل فولدر ال  tenant 
```php
database/migrations/tenant
```