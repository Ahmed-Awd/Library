<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'يجب قبول حقل ( :attribute )',
    'active_url'           => 'حقل ( :attribute ) لا يُمثّل رابطًا صحيحًا',
    'after'                => 'يجب على حقل ( :attribute ) أن يكون تاريخًا لاحقًا للتاريخ :date.',
    'after_or_equal'       => 'حقل ( :attribute ) يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha'                => 'يجب أن لا يحتوي حقل ( :attribute ) سوى على حروف',
    'alpha_dash'           => 'يجب أن لا يحتوي حقل ( :attribute ) على حروف، أرقام ومطّات.',
    'alpha_num'            => 'يجب أن يحتوي ( :attribute ) على حروفٍ وأرقامٍ فقط',
    'array'                => 'يجب أن يكون حقل ( :attribute ) ًمصفوفة',
    'before'               => 'يجب على حقل ( :attribute ) أن يكون تاريخًا سابقًا للتاريخ :date.',
    'before_or_equal'      => 'حقل ( :attribute ) يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة ( :attribute ) بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف ( :attribute ) بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد حروف النّص ( :attribute ) بين :min و :max',
        'array'   => 'يجب أن يحتوي ( :attribute ) على عدد من العناصر بين :min و :max',
    ],
    'boolean'      =>'يجب أن تكون قيمة حقل ( :attribute ) إما true أو false ',
    'confirmed'     => 'حقل التأكيد غير مُطابق للحقل ( :attribute )',
    'date'          => 'حقل ( :attribute ) ليس تاريخًا صحيحًا',
    'date_format'   => 'لا يتوافق حقل ( :attribute ) مع الشكل :format.',
    'different'     => 'يجب أن يكون حقلان ( :attribute ) و :other مُختلفان',
    'digits'        => 'يجب أن يحتوي حقل ( :attribute ) على :digits رقمًا/أرقام',
    'digits_between'=> 'يجب أن يحتوي حقل ( :attribute ) بين :min و :max رقمًا/أرقام ',
    'dimensions'    => 'الـ ( :attribute ) يحتوي على أبعاد صورة غير صالحة.',
    'distinct'      => 'للحقل ( :attribute ) قيمة مُكرّرة.',
    'email'         => 'يجب أن يكون ( :attribute ) عنوان بريد إلكتروني صحيح البُنية',
    'exists'        => 'حقل ( :attribute ) لاغٍ',
    'file'          => 'الـ ( :attribute ) يجب أن يكون من ملفا.',
    'filled'        => 'حقل ( :attribute ) إجباري',
    'gt' => [
        'numeric' => 'حقل ( :attribute ) يجب ان يكون اكبر من  :value.',
        'file' => 'حقل ( :attribute ) يجب ان يكون اكبر من  :value كبلوبايت.',
        'string' => 'حقل ( :attribute ) يجب ان يكون اكبر من  :value حروف.',
        'array' => 'حقل ( :attribute ) يجب ان يحتوي على  :value عنصر.',
    ],
    'gte' => [
        'numeric' => 'حقل ( :attribute ) يجب ان يكون اكبر من  او يساوي :value.',
        'file' => 'حقل ( :attribute ) يجب ان يكون اكبر من  او يساوي :value كيلو بايت .',
        'string' => 'حقل ( :attribute ) يجب ان يكون اكبر من  او يساوي :value حروف.',
        'array' => 'حقل ( :attribute ) يجب ان يحتوى على  :value عنضر او اكثر.',
    ],
    'image'   => 'يجب أن يكون حقل ( :attribute ) صورةً',
    'in'      => 'حقل ( :attribute ) لاغٍ',
    'in_array'=> 'حقل ( :attribute ) غير موجود في :other.',
    'integer' => 'يجب أن يكون حقل ( :attribute ) عددًا صحيحًا',
    'ip'      => 'يجب أن يكون حقل ( :attribute ) عنوان IP ذا بُنية صحيحة',
    'ipv4'    => 'يجب أن يكون حقل ( :attribute ) عنوان IPv4 ذا بنية صحيحة.',
    'ipv6'    => 'يجب أن يكون حقل ( :attribute ) عنوان IPv6 ذا بنية صحيحة.',
    'json'    => 'يجب أن يكون حقل ( :attribute ) نصا من نوع JSON.',
    'lt' => [
        'numeric' => 'The ( :attribute ) must be less than :value.',
        'file' => 'The ( :attribute ) must be less than :value kilobytes.',
        'string' => 'The ( :attribute ) must be less than :value characters.',
        'array' => 'The ( :attribute ) must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The ( :attribute ) must be less than or equal :value.',
        'file' => 'The ( :attribute ) must be less than or equal :value kilobytes.',
        'string' => 'The ( :attribute ) must be less than or equal :value characters.',
        'array' => 'The ( :attribute ) must not have more than :value items.',
    ],
    'max'                  => [
        'numeric' => 'يجب أن تكون قيمة حقل ( :attribute ) مساوية أو أصغر لـ :max.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف ( :attribute ) :max كيلوبايت',
        'string'  => 'يجب أن لا يتجاوز طول نص ( :attribute ) :max حروفٍ/حرفًا',
        'array'   => 'يجب أن لا يحتوي حقل ( :attribute ) على أكثر من :max عناصر/عنصر.',
    ],
    'mimes'                => 'يجب أن يكون حقل ملفًا من نوع : :values.',
    'mimetypes'            => 'يجب أن يكون حقل ملفًا من نوع : :values.',
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة حقل ( :attribute ) مساوية أو أكبر لـ :min.',
        'file'    => 'يجب أن يكون حجم الملف ( :attribute ) على الأقل :min كيلوبايت',
        'string'  => 'يجب أن يكون طول نص ( :attribute ) على الأقل :min حروفٍ/حرفًا',
        'array'   => 'يجب أن يحتوي حقل ( :attribute ) على الأقل على :min عُنصرًا/عناصر',
    ],
    'multiple_of' => 'حقل ( :attribute ) يجب ان يكون كمية من :value.',
    'not_regex' => 'صيغة حقل ( :attribute ) .غير صحيحة',
    'password' => 'كملة السر غير صحيحة.',
    'not_in'               => 'حقل ( :attribute ) لاغٍ',
    'numeric'              => 'يجب على حقل ( :attribute ) أن يكون رقمًا',
    'present'              => 'يجب تقديم حقل ( :attribute )',
    'regex'                => 'صيغة حقل ( :attribute ) .غير صحيحة',
    'required'             => 'حقل ( :attribute ) مطلوب.',
    'required_if'          => 'حقل ( :attribute ) مطلوب في حال ما إذا كان :other يساوي :value.',
    'required_unless'      => 'حقل ( :attribute ) مطلوب في حال ما لم يكن :other يساوي :values.',
    'required_with'        => 'حقل ( :attribute ) إذا توفّر :values.',
    'required_with_all'    => 'حقل ( :attribute ) إذا توفّر :values.',
    'required_without'     => 'حقل ( :attribute ) إذا لم يتوفّر :values.',
    'required_without_all' => 'حقل ( :attribute ) إذا لم يتوفّر :values.',
    'same'                 => 'يجب أن يتطابق حقل ( :attribute ) مع :other',
    'same' => 'The ( :attribute ) and :other must match.',
    'size'                 => [
        'numeric' => 'يجب أن تكون قيمة حقل ( :attribute ) مساوية لـ :size',
        'file'    => 'يجب أن يكون حجم الملف ( :attribute ) :size كيلوبايت',
        'string'  => 'يجب أن يحتوي النص ( :attribute ) على :size حروفٍ/حرفًا بالظبط',
        'array'   => 'يجب أن يحتوي حقل ( :attribute ) على :size عنصرٍ/عناصر بالظبط',
    ],
    'starts_with' => 'The ( :attribute ) must start with one of the following: :values.',
    'string' => 'The ( :attribute ) must be a string.',
    'timezone' => 'The ( :attribute ) must be a valid zone.',
    'unique' => 'قيمة حقل ( :attribute ) مُستخدمة من قبل',
    'uploaded' => 'فشل في تحميل الـ ( :attribute )',
    'url' => 'صيغة الرابط ( :attribute ) غير صحيحة',
    'uuid' => 'The ( :attribute ) must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name'                  => 'الاسم',
        'username'              => 'اسم المُستخدم',
        'email'                 => 'البريد الالكتروني',
        'first_name'            => 'الاسم',
        'last_name'             => 'اسم العائلة',
        'password'              => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'city'                  => 'المدينة',
        'country'               => 'الدولة',
        'address'               => 'العنوان',
        'phone'                 => 'الهاتف',
        'mobile'                => 'الجوال',
        'age'                   => 'العمر',
        'sex'                   => 'الجنس',
        'gender'                => 'النوع',
        'day'                   => 'اليوم',
        'month'                 => 'الشهر',
        'year'                  => 'السنة',
        'hour'                  => 'ساعة',
        'minute'                => 'دقيقة',
        'second'                => 'ثانية',
        'content'               => 'المُحتوى',
        'description'           => 'الوصف',
        'excerpt'               => 'المُلخص',
        'date'                  => 'التاريخ',
        'time'                  => 'الوقت',
        'available'             => 'مُتاح',
        'size'                  => 'الحجم',
        'price'                 => 'السعر',
        'desc'                  => 'نبذه',
        'title'                 => 'العنوان',
        'q'                     => 'البحث',
        'link'                  => ' ',
        'slug'                  => ' ',
        'balance'               => 'الرصيد',
        'reserved_balance'      => 'الرصيد المحجوز',
        'is_disabled'           => 'موقف ',
        'owner_id'              => ' رقم حساب المالك',
        'type_id'               => ' نوع المتجر ',
        'customer_lng'          => 'عنوان الزبون بالخريطة خط الطول',
        'customer_lat'          => ' عنوان الزبون بالخريطة خط العرض ',
        'customer_address'      => ' عنوان الزبون ',
        'building_no'           => ' رقم المبنا ',
        'apartment_no'          => 'رقم الشقة ',
        'customer_name'         => 'اسم الزبون ',
        'customer_mobile'       => 'رقم التلفون ',
        'total_price'           => ' الاجمالي ',
        'delivery_price'        => 'سعر التوصيل ',
        'distance_store_order'  => 'المسافة بين المطعم و الزبون ',
        'qr_code'               => 'رمز الاستجابة السريعة ',
        'expected_time'         => 'الوقت الموتوقع لتسليم الطلبية ',
        'comment'               => 'تعليق ',
        'status'                => 'الحالة ',
        'rate'                  => 'تقيم ',
        'package_name'          => 'اسم الباقة ',
        'value'                 => 'القيمة ',
        'price'                 => 'السعر ',
        'package_id'            => 'رقم الباقة ',
        'code'                  => 'الكود  ',
        'purchased_at'          => 'تاريخ الاستعمال ',
        'key'                   => 'مفتاح ',
        'default'               => 'قيمة افتراضية ',
        'charge_fail_attempts'  => 'عدد مرات الفشل ',
        'last_fail_charge'      => 'تاريخ اخر محاولة فاشله ',
        'type'                  => 'النوع ',
        'default_delivery_time' => 'الوقت الافتراضي للتسليم ',
        'is_available'          => 'هل متاح ',
        'country_code'          => 'كود الدولة',
        'mobile_verified_at'    => 'تاريخ التحقق ',
        'number_of_tries'       => 'عدد المحاولات ',
        'personal_photo'       => 'الصورة الشخصية',
        'license_photo'       => ' صورة البطاقة ',
        'vehicle_photo'       => ' صورة المركبة',
        'vehicle_license_photo'       => ' صورة رخصةالمركبة',
        'store_name'       => ' اسم المطعم ',
        'activity_type'       => ' نوع النشاط ',
        'location'       => ' الموقع في الخريطة',
        'owner_name'       => ' اسم المالك ',
        'owner_username'       => ' اسم المستخدم للمالك ',
        'owner_email'       => ' البريد الالكتروني للمالك ',
    ],
];
