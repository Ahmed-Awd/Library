# توثيق خيارات الوجبات

- [option category](#option_category)
- [option value](#option_value)
- [option templates](#option_templates)


<a name="option_category"></a>
## option category 
جدول لتخزين اصناف الخيارت 

| الخاصية | الوصف |
| --- | --- |
| name | اسم الصنف  |
| selection  |  نوع الاختيار ['single', 'multiple'] |
| is_primary   |  0=> secandary ,1 => primary  |
| max_selectable   |  اقصى رقم لختيار من option value  |
| restaurant_id  | رقم المطعم |

<a name="option_value"></a>
## option value

جدول لتخزين قيم  الخيارت 

| الخاصية | الوصف |
| --- | --- |
| name | اسم الخيار  |
| price  |  السعر |
| min_count   |  اقل عدد |
| max_count   |  اقصى عدد |
| option_category_id   | رقم صنف الخيار  |


<a name="option_templates"></a>
## option templates

جدول لتخزين الخيارات المؤقته  

| الخاصية | الوصف |
| --- | --- |
| name | الاسم   |
| primary_option_id    |  رقم صنف الخيار نوعه primary |
| restaurant_id  | رقم المطعم |

جدول لتخزين القيم للخيارات المؤقته 

| الخاصية | الوصف |
| --- | --- |
| name | الاسم   |
| secondary_option_id     |  رقم صنف الخيار نوعه secondary |
| primary_option_value_id   |رقم قيمة الخيار نوع الصنف primary |
| secondary_option_value_id   |رقم قيمة الخيار نوع الصنف secondary |
| option_template_id     | رقم الخيار المؤقت |
| price     | السعر  |
| use_default     |  0=> السعر الموجود في قيمة الخيار ,1 => السعر الموجود  هنا |
