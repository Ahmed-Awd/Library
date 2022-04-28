# توثيق قاعدة البيانات

- [Roles & Permissions](#roles-permissions)
- [Settings](#settings)
- [Users](#users)
- [Orders](#orders)
- [Restaurants](#Restaurants)

<a name="roles-permissions"></a>
## Roles & Permissions

![Roles & Permissions](/doc-files/v1.0/db/roles-permissions.png)

الجداول الخاصة بالرتب والصلاحيات جاهزة من spatie/laravel-permission يمكن معرفة طريقة التعامل معها بالرجوع [للتوثيق الخاص بها](https://spatie.be/docs/laravel-permission/v4/introduction).



<a name="settings"></a>
## Settings

![Settings](/doc-files/v1.0/db/settings.png)

الجدول المخصص لتخزين الاعدادات العامة للموقع القائمة التالية توضح وظيفة كل عمود:

* key => المفتاح المميز للخيار، باستخدام هذا المفتاح يمكن استدعاؤه داخل الكود بشكل مقروء دون التعامل مع الـ id
* name => اسم العرض الذي سيتم عرضه في لوحة التحكم للخيار.
* value => قيمة الخيار، يمكن تعديلها من لوحة التحكم.
* default => القيمة الأصلية/الافتراضية للخيار، الهدف منها امكانية عمل Reset لخيارات الموقع بحيث ترجع الاعدادات الافتراضية.

<a name="users"></a>
## Users

![Users](/doc-files/v1.0/db/users.png)

الجدول الخاص بالمستخدمين، يتم تخزين جميع انواع المستخدمين في هذا الجدول، ويتم التفريق بينهم باستخدام Role المرتبطة بكل مستخدم، القائمة التالية
توضح بعض الخصائص لهذا الجدول:

| الخاصية | الوصف |
| --- | --- |
| is_disabled | في حال كانت قيمتها 1، يتم تعطيل المستخدم، |
| phone  | رقم التلفون |
| country_code   | كود رقم التلفون |


<a name="restaurants"></a>
## restaurants

![restaurants](/doc-files/v1.0/db/restaurants.png)

تخزن بيانات المتجر واعداداته ,category_id تمثل نوع المطعم  . 