#تثبيت المشروع



<a name="section-1"></a>
##git

عنوان ال git repository على موقع bitbucket هوا

https://bitbucket.org/smartlivesws/min-pizza-web/src/master/

و تقوم بتنزيل ال repo عن طريق ال git

```php
https://bitbucket.org/smartlivesws/min-pizza-web/src/master/
```

<a name="section-2"></a>
##composer
بعد تنزيل ال repo و الدخول على ال directory الخلص بها

تقوم بتنصيب ال packages الخاصة ب composer عن طريق ال command
```php
composer install
```

<a name="section-3"></a>
##npm
تقوم بتنصيب ال packages الخاصة ب npm عن طريق ال command

```php
npm install
```

<a name="section-4"></a>
##.env
لأنشاء ملف .env قم بنسخ ملف .env.example من داخل ال directory الخاص ب ال repo و نسخه معا اعادة التسمية الى .env

ثم قم بتفعيل ال command الأتي

```php
php artisan key:generate
```

<a name="section-5"></a>
##DB migrate

تثبيت جداول قاعدة البيانات عن طريق ال artisan migrate بال command التالى

```php
php artisan migrate
```

ثم نظيف بيانات الدول عن طريق command
```php
php artisan world:init
``` 
ثم نقوم بأضافة البيانات عن طريق ال seeders الموجودة عن طريق command 

```php
php artisan DB:seed
```
