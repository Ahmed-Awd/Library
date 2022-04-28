#ادارة الإعدادات

---
نظام الاعدادات هو نظام هام لكل مدرسة حيث يتم عمل اعدادات للمدرسة وتحديد بياناتها الخاصة بهامثل(اللوجو- اسم المدرسة - عنوان المدرسة - ارقام التواصل- .. ) وغيرها من اعدادات المزايا 

<a name="section-1"></a>
##packages
نعتمد في نظام الاعدادات علي 
[laravel settings](https://github.com/spatie/laravel-settings) package
<a name="section-1"></a>
##controller

```php
Http/Controllers/SettingController.php
```


ال repository المسؤلة عن كل ما يتعلق بعمليات ال subjects مع ال database فى

```php
app/Repositories/SettingRepository.php
```
<a name="section-4"></a>

<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/site-settings                | GET   |  showSiteSettings   |  show settings | view site settings        
| api/site-settings                | PATCH    |  updateSiteSettings    | edit settings | edit site settings  
