<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'required_with' => 'حقل :attribute مطلوب.',
    'email' => 'حقل :attribute يجب أن يكون بريدًا إلكترونيًا صحيحًا.',
    'url' => 'حقل :attribute يجب أن يكون رابطًا صحيحًا يبدأ بـ https://',
    'unique' => ':attribute مستخدم من قبل.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'current_password' => 'كلمة المرور الحالية غير صحيحة.',
    'image' => ':attribute يجب أن يكون صورة.',
    'file' => ':attribute يجب أن يكون ملفًا.',
    'uploaded' => 'فشل رفع :attribute — قد يكون الحجم أكبر من المسموح في الاستضافة.',
    'mimes' => ':attribute يجب أن يكون من نوع: :values.',
    'max' => [
        'string' => ':attribute يجب ألا يتجاوز :max حرفًا.',
        'file' => 'حجم :attribute يجب ألا يتجاوز :max كيلوبايت.',
        'numeric' => ':attribute يجب ألا يتجاوز :max.',
        'array' => ':attribute يجب ألا يحتوي على أكثر من :max عنصر.',
    ],
    'min' => ['string' => ':attribute يجب أن يكون :min أحرف على الأقل.'],
    'regex' => 'صيغة :attribute غير صحيحة.',
    'numeric' => ':attribute يجب أن يكون رقمًا.',
    'date' => ':attribute ليس تاريخًا صحيحًا.',
    'boolean' => 'قيمة :attribute غير صحيحة.',
    'array' => 'قيمة :attribute غير صحيحة.',
    'string' => ':attribute يجب أن يكون نصًا.',
    'in' => 'القيمة المختارة في :attribute غير صحيحة.',
    'password' => [
        'letters' => ':attribute يجب أن يحتوي على حرف واحد على الأقل.',
        'numbers' => ':attribute يجب أن يحتوي على رقم واحد على الأقل.',
    ],
    'attributes' => [],
];
