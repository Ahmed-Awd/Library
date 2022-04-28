#ادارة الإستيراد

---
تقدم المنصة خاصية اضافة (طلاب - معلمين - اسئلة - تقارير) عن طريق ملف excel

<a name="section-1"></a>
##controller

```php
Http/Controllers/ImportController.php
```
ال policy الخاصة ب ال importing تضاف داخل ال function المسؤلة عن الاستيراد

```php
public function storeStudents(Request $request) : JsonResponse{
    $this->authorize('import users');
```

ال repository المسؤلة عن كل ما يتعلق بعمليات ال importing مع ال database فى

```php
app/Repositories/ImportRepository.php
```


<a name="section-2"></a>
##API's and Routes

| route | method   | function | policy | details
| : |   :-   |  :  | : | : |
| api/import/students                | POST   |  storeStudents   |  import users | store students via excel        
| api/import/staff                | POST    |  storeStaff    | import users | store staff via excel   

<a name="section-3"></a>
##store requests
هذا جدول بال templates المطلوبة فى حالة عمل استيراد


| name    | file    
| :           |   :  
| staff        | [staff-excel](https://app.alamlms.com/common/downloads/excel-templates/teachers_noor.xlsx)
| students        | [students-excel](https://app.alamlms.com/common/downloads/excel-templates/student_noor_new.xlsx)
