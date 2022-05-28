# صفحات وواجهات لوحة تحكم الآدمن

- 

> {info} ستتم إضافة صور للواجهات بعد تجهيزها

## الرئيسية
الصفحة الرئيسية في لوحة تحكم الإدارة أو صفحة الـ (Dashboard) هي أول واجهة تُفتح بعد تسجيل الدخول، ويتم فيها عرض بعض الاحصائيات
العامة عن الموقع، الإحصائيات التي سيتم توفيرها في هذه النسخة هي:

* عدد المتاجر
* عدد السائقين
* عدد الباقات التي تم شراؤها خلال الشهر
* مجموع أسعار الباقات التي تم شراؤها خلال الشهر (أرباح الباقات)

## المتاجر

صفحة المتاجر تعرض جدولا بالمتاجر المتوفرة يعرض الجدول الأعمدة التالية:
* اسم المتجر
* اسم صاحب المتجر
* نوع النشاط
* الرصيد والرصيد المحجوز
* عمود تعرض فيه حالة المتجر مع امكانية تغييرها (حالة المتجر هي قدرة المتجر على طلب سائقين أم لا، يمكن تعطيل المتجر بحيث يستطيع تصفح لوحة التحكم أو التطبيق لكن دون القدرة على طلب سائقين)
* عمود يعرض فيه زر الحذف وزر التعديل

وتوجد أيضا في لوحة التحكم صفحة لإضافة متجر، وأخرى للتعديل.

عند الضغط على المتجر، تظهر قائمة طلبات ذلك المتجر، وهي نفس قائمة الطلبات الموجودة في [لوحة تحكم أصحاب المتاجر](/{{route}}/{{version}}/UI/store-owner-web)
ويستطيع الآدمن إسناد الطلب لسائق محدد من هذه الصفحة

## السائقون

صفحة السائقين تعرض جدولا بالمتاجر المتوفرة يعرض الجدول الأعمدة التالية:
* اسم السائق
* رصيد السائق
* حالة السائق  (مفعل/معطل)
* عمود يعرض فيه زر الحذف وزر التعديل

وتوجد أيضا في لوحة التحكم صفحة لإضافة سائق، وأخرى للتعديل.

## الباقات
هذه الصفحة تعرض الباقات المتوفرة، حيث تعرض أسماء الباقات  ونوع الباقة (مالك او سائق )مع زر للحذف أو التعديل في كل باقة،
عند الضغط على الباقة ستفتح صفحة اكواد الباقة، وفيها قائمة الأكواد المتوفرة، ويوجد زر "إنشاء كود جديد" يقوم بانشاء كود عشوائي تلقائيا""

## الإعدادات

إعدادات عامة للنظام كاملا، الإعدادات المتوفرة حاليا:

* نسبة الخصم من سعر التوصيل للتاجر
* نسبة الخصم من سعر التوصيل للسائق
* سعر التوصيل الابتدائي
* سعر التوصيل لكل كيلومتر إضافي
* المسافة الابتدائية
* المدة التي سيتم إشعار الآدمن خلالها في حال لم يتم اسناد الطلب لأي سائق
* المدة التي سيتم إشعار الآدمن خلالها في حال ظلت حالة الطلب السائق في الطريق الى التاجر
* المدة التي سيتم إلغاء الطلب خلالها في حال لم يتم اسناد الطلب لأي سائق
* المدة التي سيتم خلالها تحويل الطلب إلى "تم التسليم" بشكل تلقائي