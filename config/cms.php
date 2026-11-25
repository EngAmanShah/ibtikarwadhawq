<?php

/*
|--------------------------------------------------------------------------
| مخطط محتوى الموقع
|--------------------------------------------------------------------------
| كل صفحة إعدادات في لوحة التحكم تُبنى تلقائيًا من هذا الملف.
| field types: text, textarea, image, file, color, url, email, phone, code, toggle, list
*/

return [

    'pages' => [

        /* ============================== الصفحة الرئيسية ============================== */
        'home' => [
            'title' => 'الصفحة الرئيسية',
            'icon' => 'fa-house-chimney',
            'description' => 'كل ما يظهر للزائر عند فتح الموقع لأول مرة، مقسّم بنفس ترتيب ظهوره في الصفحة.',
            'preview' => '/',
            'related' => [
                ['label' => 'الخدمات الرئيسية', 'route' => 'admin.services.index', 'icon' => 'fa-briefcase'],
                ['label' => 'شريط خدمات التشطيب المتحرك', 'route' => 'admin.slides.index', 'icon' => 'fa-film'],
                ['label' => 'شعارات العملاء', 'route' => 'admin.clients.index', 'icon' => 'fa-handshake'],
            ],
            'sections' => [
                [
                    'title' => 'الواجهة الرئيسية (أول ما يراه الزائر)', 'icon' => 'fa-panorama',
                    'hint' => 'الجزء الكبير أعلى الصفحة: العنوان الرئيسي والزرين والصور الثلاث.',
                    'fields' => [
                        ['key' => 'home_hero_eyebrow', 'label' => 'السطر الصغير فوق العنوان', 'type' => 'text', 'default' => 'ابتكار وذوق للمقاولات'],
                        ['key' => 'home_hero_title', 'label' => 'العنوان الرئيسي', 'type' => 'textarea', 'rows' => 2, 'default' => 'نصنع التفاصيل التي تمنح المساحات قيمة وجمالًا يدوم', 'help' => 'اجعله قصيرًا وواضحًا — أفضل طول بين 6 و 12 كلمة.'],
                        ['key' => 'home_hero_text', 'label' => 'الفقرة التعريفية', 'type' => 'textarea', 'default' => 'نتعامل مع كل مساحة كمشروع مستقل؛ نبدأ بفهم الاحتياج، ثم نختار الخامات والمقاسات المناسبة، وننفذ التفاصيل بتسلسل واضح يحافظ على جودة النتيجة النهائية.'],
                        ['key' => 'home_hero_btn1', 'label' => 'نص الزر الأول (يفتح صفحة الخدمات)', 'type' => 'text', 'default' => 'استعرض نطاق الأعمال', 'half' => true],
                        ['key' => 'home_hero_btn2', 'label' => 'نص الزر الثاني (يفتح صفحة التواصل)', 'type' => 'text', 'default' => 'تواصل معنا', 'half' => true],
                        ['key' => 'home_hero_image1', 'label' => 'الصورة الكبيرة', 'type' => 'image', 'default' => 'assets/images/service-details/design-and-decor-square.webp', 'third' => true],
                        ['key' => 'home_hero_image2', 'label' => 'الصورة الثانية', 'type' => 'image', 'default' => 'assets/images/services/decoration.webp', 'third' => true],
                        ['key' => 'home_hero_image3', 'label' => 'الصورة الثالثة', 'type' => 'image', 'default' => 'assets/images/riyadh-villa-facade-square-compressed.webp', 'third' => true],
                    ],
                ],
                [
                    'title' => 'قسم «نبذة عن ابتكار وذوق»', 'icon' => 'fa-building', 'toggle' => 'home_show_about',
                    'fields' => [
                        ['key' => 'home_about_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'نبذة عن ابتكار وذوق'],
                        ['key' => 'home_about_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'تنفيذ داخلي يجمع بين الاستخدام العملي والمظهر المتناسق'],
                        ['key' => 'home_about_text1', 'label' => 'الفقرة الأولى', 'type' => 'textarea', 'default' => 'ابتكار وذوق متخصصة في أعمال التشطيب، التصميم الداخلي، الديكور، والأعمال الخشبية، وتقدم حلولًا متكاملة للمساحات السكنية والتجارية بجودة عالية واهتمام واضح بالتفاصيل.'],
                        ['key' => 'home_about_text2', 'label' => 'الفقرة الثانية', 'type' => 'textarea', 'default' => 'نبدأ كل مشروع بقراءة المساحة وتحديد احتياجها الفعلي، ثم نختار الخامات والمعالجات المناسبة حتى يخرج التنفيذ النهائي منظمًا ومتكاملًا.'],
                        ['key' => 'home_about_btn', 'label' => 'نص الزر', 'type' => 'text', 'default' => 'معرفة المزيد', 'half' => true],
                        ['key' => 'home_about_image', 'label' => 'صورة القسم', 'type' => 'image', 'default' => 'assets/images/about-facade.webp', 'half' => true],
                    ],
                ],
                [
                    'title' => 'قسم «نطاق أعمالنا»', 'icon' => 'fa-briefcase', 'toggle' => 'home_show_services',
                    'hint' => 'بطاقات الخدمات نفسها تُدار من صفحة «الخدمات».',
                    'fields' => [
                        ['key' => 'home_services_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'نطاق أعمالنا'],
                        ['key' => 'home_services_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'خدمات رئيسية تغطي احتياجات التشطيب والديكور'],
                        ['key' => 'home_services_desc', 'label' => 'الوصف', 'type' => 'textarea', 'default' => 'تم تنظيم الخدمات في أقسام واضحة ليسهل على العميل الوصول إلى الخدمة المناسبة حسب طبيعة المشروع.'],
                    ],
                ],
                [
                    'title' => 'شريط «خدمات التشطيب المتميز» المتحرك', 'icon' => 'fa-film', 'toggle' => 'home_show_finishing',
                    'hint' => 'الصور المتحركة نفسها تُدار من صفحة «شريط التشطيبات».',
                    'fields' => [
                        ['key' => 'home_finishing_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'مجموعة من خدمات التشطيب المتميز'],
                        ['key' => 'home_finishing_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'تفاصيل تنفيذية تتحرك ضمن منظومة واحدة'],
                    ],
                ],
                [
                    'title' => 'قسم «عملاؤنا»', 'icon' => 'fa-handshake', 'toggle' => 'home_show_clients',
                    'hint' => 'الشعارات نفسها تُدار من صفحة «العملاء».',
                    'fields' => [
                        ['key' => 'home_clients_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'عملاؤنا'],
                        ['key' => 'home_clients_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'عملاؤنا من الجهات الحكومية والشركات الكبرى'],
                    ],
                ],
                [
                    'title' => 'قسم «تفاصيل التشطيب»', 'icon' => 'fa-eye', 'toggle' => 'home_show_details',
                    'fields' => [
                        ['key' => 'home_details_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'تفاصيل التشطيب'],
                        ['key' => 'home_details_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'الجودة تظهر في التقفيلة القريبة وليس في الصورة البعيدة'],
                        ['key' => 'home_details_desc', 'label' => 'الوصف', 'type' => 'textarea', 'default' => 'الفرق بين تشطيب عادي وتشطيب متقن يظهر عند الاقتراب: زاوية نظيفة، حافة مستوية، لون منسجم مع الإضاءة، وخامة متجانسة مع ما حولها.'],
                        ['key' => 'home_details_points', 'label' => 'النقاط (علامات الصح)', 'type' => 'list', 'default' => ['تناسق الخامات', 'نظافة الزوايا', 'دقة المقاسات', 'جودة التقفيلات'], 'help' => 'كل سطر يظهر كنقطة مستقلة بجانبها علامة ✓'],
                        ['key' => 'home_details_image', 'label' => 'صورة القسم', 'type' => 'image', 'default' => 'assets/images/finishing-details-4x3.webp'],
                    ],
                ],
                [
                    'title' => 'قسم «الوثائق الرسمية»', 'icon' => 'fa-folder-open', 'toggle' => 'home_show_docs',
                    'fields' => [
                        ['key' => 'home_docs_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'الوثائق الرسمية'],
                        ['key' => 'home_docs_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'مسجلة رسميًا بوثائق قابلة للعرض والتحقق'],
                        ['key' => 'home_docs_desc', 'label' => 'الوصف', 'type' => 'textarea', 'default' => 'السجل التجاري، شهادة الالتزام، التوطين، الضريبة، المديونيات، والسلامة المهنية — كلها متاحة للاطلاع في صفحة الوثائق.'],
                        ['key' => 'home_docs_btn', 'label' => 'نص الزر', 'type' => 'text', 'default' => 'عرض الوثائق الرسمية'],
                    ],
                ],
            ],
        ],

        /* ============================== من نحن ============================== */
        'about' => [
            'title' => 'صفحة من نحن',
            'icon' => 'fa-building-user',
            'description' => 'تعريف الشركة وفريق العمل والرؤية والقيم.',
            'preview' => '/about',
            'related' => [
                ['label' => 'بطاقات المميزات (4 بطاقات)', 'route' => 'admin.blocks.index', 'params' => ['group' => 'about_feature'], 'icon' => 'fa-table-cells-large'],
                ['label' => 'الرؤية والرسالة ومراحل العمل', 'route' => 'admin.blocks.index', 'params' => ['group' => 'about_card'], 'icon' => 'fa-bullseye'],
                ['label' => 'قيمنا', 'route' => 'admin.blocks.index', 'params' => ['group' => 'about_value'], 'icon' => 'fa-heart'],
            ],
            'sections' => [
                [
                    'title' => 'رأس الصفحة', 'icon' => 'fa-heading',
                    'fields' => [
                        ['key' => 'about_hero_title', 'label' => 'العنوان الرئيسي', 'type' => 'text', 'default' => 'متخصصة في التشطيبات والتصميم الداخلي والأعمال الخشبية'],
                        ['key' => 'about_hero_text', 'label' => 'النص التعريفي', 'type' => 'textarea', 'default' => 'نوضح هنا طريقة تفكيرنا في المساحة، وكيف نتخذ قرارات التنفيذ من المعاينة وحتى التسليم النهائي.'],
                    ],
                ],
                [
                    'title' => 'نبذة عن الشركة', 'icon' => 'fa-building',
                    'fields' => [
                        ['key' => 'about_intro_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'نبذة عن الشركة'],
                        ['key' => 'about_intro_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'تنفيذ داخلي يجمع بين الاستخدام العملي والمظهر المتناسق'],
                        ['key' => 'about_intro_text1', 'label' => 'الفقرة الأولى', 'type' => 'textarea', 'default' => 'ابتكار وذوق متخصصة في أعمال التشطيب، التصميم الداخلي، الديكور، والأعمال الخشبية، وتقدم حلولًا متكاملة للمساحات السكنية والتجارية بجودة عالية واهتمام واضح بالتفاصيل.'],
                        ['key' => 'about_intro_text2', 'label' => 'الفقرة الثانية', 'type' => 'textarea', 'default' => 'تشمل خدماتنا اللياسة والسيراميك والدهانات والجبس بورد والسباكة والكهرباء والعزل والمظلات والسواتر، إضافة إلى الأبواب والأعمال الخشبية والخزائن، مع الحرص على أن تجمع النتيجة بين الجمال والعملية.'],
                        ['key' => 'about_intro_image', 'label' => 'صورة القسم', 'type' => 'image', 'default' => 'assets/images/about-facade.webp'],
                    ],
                ],
                [
                    'title' => 'فريق العمل', 'icon' => 'fa-helmet-safety', 'toggle' => 'about_show_team',
                    'fields' => [
                        ['key' => 'about_team_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'فريق العمل'],
                        ['key' => 'about_team_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'مهندسون متخصصون وفنيون يعملون بتفاصيل دقيقة'],
                        ['key' => 'about_team_text1', 'label' => 'الفقرة الأولى', 'type' => 'textarea', 'default' => 'يعتمد فريق ابتكار وذوق على مهندسين متخصصين وفنيين ذوي خبرة في التشطيب والتصميم والتنفيذ، يعملون وفق خطوات واضحة تبدأ من دراسة المساحة ورفع المقاسات حتى اختيار الخامات ومتابعة جودة التنفيذ.'],
                        ['key' => 'about_team_text2', 'label' => 'الفقرة الثانية', 'type' => 'textarea', 'default' => 'وجود فريق منظم يساعد على ضبط التسلسل بين الأعمال المدنية، الجبس، الكهرباء، السباكة، الدهانات، والأعمال الخشبية، بما يضمن نتيجة متناسقة وقابلة للاستخدام اليومي براحة وأمان.'],
                        ['key' => 'about_team_image', 'label' => 'صورة الفريق', 'type' => 'image', 'default' => 'assets/images/team-engineer-logo-back.webp'],
                    ],
                ],
                [
                    'title' => 'قسم «قيمنا»', 'icon' => 'fa-heart',
                    'hint' => 'القيم نفسها تُدار من زر «قيمنا» في الأعلى.',
                    'fields' => [
                        ['key' => 'about_values_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'قيمنا'],
                        ['key' => 'about_values_title', 'label' => 'العنوان الرئيسي للقسم', 'type' => 'text', 'default' => 'مبادئ نلتزم بها في كل مشروع ننفذه'],
                    ],
                ],
            ],
        ],

        /* ============================== صفحة الخدمات ============================== */
        'services_page' => [
            'title' => 'صفحة نطاق الأعمال',
            'icon' => 'fa-briefcase',
            'description' => 'نصوص صفحة الخدمات العامة والأزرار المشتركة في صفحات تفاصيل الخدمات.',
            'preview' => '/services',
            'related' => [
                ['label' => 'إدارة الخدمات وتفاصيلها', 'route' => 'admin.services.index', 'icon' => 'fa-briefcase'],
            ],
            'sections' => [
                [
                    'title' => 'صفحة الخدمات', 'icon' => 'fa-border-all',
                    'fields' => [
                        ['key' => 'services_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'نطاق أعمالنا'],
                        ['key' => 'services_title', 'label' => 'العنوان الرئيسي', 'type' => 'text', 'default' => 'خدمات رئيسية تغطي احتياجات التشطيب والديكور'],
                        ['key' => 'services_desc', 'label' => 'الوصف', 'type' => 'textarea', 'default' => 'تم تنظيم الخدمات في أقسام واضحة ليسهل على العميل الوصول إلى الخدمة المناسبة حسب طبيعة المشروع.'],
                        ['key' => 'services_card_btn', 'label' => 'نص زر بطاقة الخدمة', 'type' => 'text', 'default' => 'معرفة تفاصيل الخدمة'],
                    ],
                ],
                [
                    'title' => 'أزرار صفحات تفاصيل الخدمة', 'icon' => 'fa-hand-pointer',
                    'hint' => 'تظهر هذه الأزرار في كل صفحة خدمة.',
                    'fields' => [
                        ['key' => 'service_btn_consult', 'label' => 'زر طلب الاستشارة', 'type' => 'text', 'default' => 'اطلب استشارة', 'half' => true],
                        ['key' => 'service_btn_all', 'label' => 'زر كل الخدمات', 'type' => 'text', 'default' => 'كل الخدمات', 'half' => true],
                        ['key' => 'service_strip_btn', 'label' => 'زر الشريط السفلي', 'type' => 'text', 'default' => 'تواصل معنا'],
                    ],
                ],
            ],
        ],

        /* ============================== الوثائق ============================== */
        'documents_page' => [
            'title' => 'صفحة الوثائق الرسمية',
            'icon' => 'fa-file-shield',
            'description' => 'رأس صفحة الوثائق. الوثائق نفسها وبيانات السجل تُدار من الأزرار بالأعلى.',
            'preview' => '/documents',
            'related' => [
                ['label' => 'الوثائق والشهادات', 'route' => 'admin.documents.index', 'icon' => 'fa-file-pdf'],
                ['label' => 'بيانات السجل (الشريط العلوي)', 'route' => 'admin.blocks.index', 'params' => ['group' => 'company_fact'], 'icon' => 'fa-fingerprint'],
            ],
            'sections' => [
                [
                    'title' => 'رأس الصفحة', 'icon' => 'fa-heading',
                    'fields' => [
                        ['key' => 'documents_hero_title', 'label' => 'العنوان الرئيسي', 'type' => 'text', 'default' => 'وثائق الشركة الرسمية متاحة للاطلاع والتحقق'],
                        ['key' => 'documents_hero_text', 'label' => 'النص التعريفي', 'type' => 'textarea', 'default' => 'جميع الشهادات والوثائق الرسمية معروضة بروابط مباشرة للعرض أو التحميل.'],
                        ['key' => 'documents_btn', 'label' => 'نص زر فتح الوثيقة', 'type' => 'text', 'default' => 'عرض الوثيقة'],
                    ],
                ],
            ],
        ],

        /* ============================== تواصل معنا ============================== */
        'contact_page' => [
            'title' => 'صفحة تواصل معنا',
            'icon' => 'fa-envelope-open-text',
            'description' => 'نصوص صفحة التواصل ونموذج الطلب. الفروع والأرقام تُدار من صفحة «الفروع».',
            'preview' => '/contact',
            'related' => [
                ['label' => 'الفروع والأرقام والخرائط', 'route' => 'admin.branches.index', 'icon' => 'fa-map-location-dot'],
                ['label' => 'الطلبات الواردة من النموذج', 'route' => 'admin.leads.index', 'icon' => 'fa-inbox'],
            ],
            'sections' => [
                [
                    'title' => 'رأس الصفحة', 'icon' => 'fa-heading',
                    'fields' => [
                        ['key' => 'contact_hero_title', 'label' => 'العنوان الرئيسي', 'type' => 'text', 'default' => 'أرسل لنا تفاصيل مشروعك ونرتّب معك الخطوة التالية'],
                        ['key' => 'contact_branches_label', 'label' => 'عنوان قسم الفروع الصغير', 'type' => 'text', 'default' => 'بيانات الفروع', 'half' => true],
                        ['key' => 'contact_branches_title', 'label' => 'عنوان قسم الفروع', 'type' => 'text', 'default' => 'أرقام التواصل والعناوين الأساسية', 'half' => true],
                    ],
                ],
                [
                    'title' => 'نموذج الطلب', 'icon' => 'fa-pen-to-square',
                    'hint' => 'عند إرسال النموذج يُحفظ الطلب في لوحة التحكم ويُفتح واتساب للعميل برسالة جاهزة.',
                    'fields' => [
                        ['key' => 'contact_form_label', 'label' => 'العنوان الصغير', 'type' => 'text', 'default' => 'نموذج الطلب', 'half' => true],
                        ['key' => 'contact_form_title', 'label' => 'عنوان النموذج', 'type' => 'text', 'default' => 'تفاصيل بسيطة تكفي كبداية', 'half' => true],
                        ['key' => 'contact_form_button', 'label' => 'نص زر الإرسال', 'type' => 'text', 'default' => 'إرسال الطلب', 'half' => true],
                        ['key' => 'contact_form_whatsapp', 'label' => 'رقم الواتساب الذي تصله الطلبات', 'type' => 'phone', 'default' => '966535193153', 'half' => true, 'help' => 'بالصيغة الدولية بدون + أو مسافات، مثال: 966535193153'],
                        ['key' => 'contact_space_options', 'label' => 'خيارات «نوع المساحة»', 'type' => 'list', 'default' => ['سكنية', 'تجارية', 'مكتب', 'أخرى']],
                        ['key' => 'contact_form_success', 'label' => 'رسالة النجاح بعد الإرسال', 'type' => 'text', 'default' => 'تم استلام طلبك بنجاح، سنتواصل معك قريبًا.'],
                    ],
                ],
                [
                    'title' => 'بطاقة «معرفة المزيد»', 'icon' => 'fa-circle-info',
                    'fields' => [
                        ['key' => 'contact_more_text', 'label' => 'النص', 'type' => 'textarea', 'rows' => 2, 'default' => 'تعرف أكثر على أسلوب العمل والخدمات التي نقدمها قبل بدء مشروعك.'],
                        ['key' => 'contact_more_btn', 'label' => 'نص الزر', 'type' => 'text', 'default' => 'معرفة المزيد'],
                    ],
                ],
            ],
        ],

        /* ============================== الهوية ============================== */
        'identity' => [
            'title' => 'الهوية والشعارات',
            'icon' => 'fa-swatchbook',
            'description' => 'اسم الشركة والشعارات والألوان وملف البروفايل.',
            'preview' => '/',
            'group' => 'settings',
            'sections' => [
                [
                    'title' => 'الاسم', 'icon' => 'fa-signature',
                    'fields' => [
                        ['key' => 'site_name', 'label' => 'الاسم الكامل للشركة', 'type' => 'text', 'default' => 'ابتكار وذوق للمقاولات'],
                        ['key' => 'brand_name', 'label' => 'الاسم في الشعار (السطر الأول)', 'type' => 'text', 'default' => 'ابتكار وذوق', 'half' => true],
                        ['key' => 'brand_suffix', 'label' => 'السطر الثاني تحت الاسم', 'type' => 'text', 'default' => 'للمقاولات', 'half' => true],
                    ],
                ],
                [
                    'title' => 'الشعارات', 'icon' => 'fa-image',
                    'fields' => [
                        ['key' => 'logo_icon', 'label' => 'شعار القائمة العلوية', 'type' => 'image', 'default' => 'assets/images/brand/logo-icon.png', 'third' => true, 'keep' => true],
                        ['key' => 'footer_logo', 'label' => 'شعار أسفل الموقع', 'type' => 'image', 'default' => 'assets/images/brand/footer-logo.png', 'third' => true, 'keep' => true],
                        ['key' => 'favicon', 'label' => 'أيقونة المتصفح (Favicon)', 'type' => 'image', 'default' => 'assets/images/brand/favicon.png', 'third' => true, 'keep' => true, 'help' => 'صورة مربعة صغيرة، يفضّل 512×512'],
                    ],
                ],
                [
                    'title' => 'الألوان', 'icon' => 'fa-palette',
                    'hint' => 'غيّر لون الهوية في كل الموقع بضغطة واحدة.',
                    'fields' => [
                        ['key' => 'primary_color', 'label' => 'اللون الأساسي', 'type' => 'color', 'default' => '#bfa27e', 'half' => true],
                        ['key' => 'primary_dark', 'label' => 'اللون الأساسي الداكن (عند المرور بالماوس)', 'type' => 'color', 'default' => '#a88c66', 'half' => true],
                    ],
                ],
                [
                    'title' => 'البروفايل والفوتر', 'icon' => 'fa-file-pdf',
                    'fields' => [
                        ['key' => 'profile_pdf', 'label' => 'ملف البروفايل (PDF)', 'type' => 'file', 'default' => 'assets/images/documents/profile.pdf', 'help' => 'يفتح عند الضغط على زر البروفايل أعلى الموقع.'],
                        ['key' => 'profile_label', 'label' => 'نص زر البروفايل', 'type' => 'text', 'default' => 'البروفايل', 'half' => true],
                        ['key' => 'copyright_text', 'label' => 'نص حقوق النشر', 'type' => 'text', 'default' => 'ابتكار وذوق للمقاولات', 'half' => true],
                        ['key' => 'footer_text', 'label' => 'النبذة أسفل الموقع', 'type' => 'textarea', 'rows' => 2, 'default' => 'تشطيبات وديكور وأعمال خشبية بتفاصيل واضحة بين الخامة والمقاس والذوق.'],
                    ],
                ],
            ],
        ],

        /* ============================== التواصل والسوشيال ============================== */
        'contact_info' => [
            'title' => 'التواصل والسوشيال ميديا',
            'icon' => 'fa-address-book',
            'description' => 'البريد، الواتساب الرئيسي، وحسابات التواصل الاجتماعي. الحقل الفارغ لا يظهر في الموقع.',
            'preview' => '/contact',
            'group' => 'settings',
            'related' => [
                ['label' => 'أرقام الفروع', 'route' => 'admin.branches.index', 'icon' => 'fa-map-location-dot'],
            ],
            'sections' => [
                [
                    'title' => 'بيانات أساسية', 'icon' => 'fa-phone',
                    'fields' => [
                        ['key' => 'email', 'label' => 'البريد الإلكتروني', 'type' => 'email', 'default' => 'info@ebtkar-wadhawq.com', 'half' => true],
                        ['key' => 'whatsapp_main', 'label' => 'رقم الواتساب الرئيسي', 'type' => 'phone', 'default' => '966535193153', 'half' => true, 'help' => 'بالصيغة الدولية بدون + : 9665XXXXXXXX'],
                        ['key' => 'floating_label', 'label' => 'نص زر التواصل العائم', 'type' => 'text', 'default' => 'تواصل معنا', 'half' => true],
                        ['key' => 'phone_main', 'label' => 'الهاتف الرئيسي (لمحركات البحث)', 'type' => 'phone', 'default' => '+966535193153', 'half' => true],
                    ],
                ],
                [
                    'title' => 'حسابات التواصل الاجتماعي', 'icon' => 'fa-share-nodes',
                    'fields' => [
                        ['key' => 'social_instagram', 'label' => 'إنستجرام', 'type' => 'url', 'icon' => 'fa-brands fa-instagram', 'default' => 'https://www.instagram.com/abtikar_wadhawq', 'half' => true],
                        ['key' => 'social_snapchat', 'label' => 'سناب شات', 'type' => 'url', 'icon' => 'fa-brands fa-snapchat', 'default' => 'https://www.snapchat.com/add/abtikar_wadhaw', 'half' => true],
                        ['key' => 'social_tiktok', 'label' => 'تيك توك', 'type' => 'url', 'icon' => 'fa-brands fa-tiktok', 'default' => 'https://www.tiktok.com/@abtikar_wadhawq', 'half' => true],
                        ['key' => 'social_x', 'label' => 'منصة X', 'type' => 'url', 'icon' => 'fa-brands fa-x-twitter', 'default' => 'https://x.com/abtikar_wadhawq', 'half' => true],
                        ['key' => 'social_facebook', 'label' => 'فيسبوك', 'type' => 'url', 'icon' => 'fa-brands fa-facebook-f', 'default' => '', 'half' => true],
                        ['key' => 'social_linkedin', 'label' => 'لينكد إن', 'type' => 'url', 'icon' => 'fa-brands fa-linkedin-in', 'default' => '', 'half' => true],
                        ['key' => 'social_youtube', 'label' => 'يوتيوب', 'type' => 'url', 'icon' => 'fa-brands fa-youtube', 'default' => '', 'half' => true],
                    ],
                ],
            ],
        ],

        /* ============================== SEO ============================== */
        'seo' => [
            'title' => 'الظهور في جوجل (SEO)',
            'icon' => 'fa-magnifying-glass-chart',
            'description' => 'العناوين والأوصاف التي تظهر في نتائج البحث، وأكواد التتبع.',
            'group' => 'settings',
            'sections' => [
                [
                    'title' => 'إعدادات عامة', 'icon' => 'fa-globe',
                    'fields' => [
                        ['key' => 'site_url', 'label' => 'رابط الموقع الأساسي', 'type' => 'url', 'default' => 'https://ebtkar-wadhawq.com', 'help' => 'يستخدم في الروابط الأساسية لمحركات البحث (Canonical).'],
                        ['key' => 'og_image', 'label' => 'صورة المشاركة الافتراضية', 'type' => 'image', 'default' => 'assets/images/about-facade.webp', 'help' => 'تظهر عند مشاركة رابط الموقع في واتساب وتويتر.'],
                        ['key' => 'seo_city', 'label' => 'المدينة', 'type' => 'text', 'default' => 'الجبيل', 'third' => true],
                        ['key' => 'seo_region', 'label' => 'المنطقة', 'type' => 'text', 'default' => 'المنطقة الشرقية', 'third' => true],
                        ['key' => 'seo_postal', 'label' => 'الرمز البريدي', 'type' => 'text', 'default' => '35514', 'third' => true],
                        ['key' => 'seo_areas', 'label' => 'المدن التي تخدمها الشركة', 'type' => 'list', 'default' => ['الجبيل', 'الأحساء']],
                    ],
                ],
                [
                    'title' => 'عناوين ووصف الصفحات', 'icon' => 'fa-heading', 'seo' => true,
                    'hint' => 'العنوان المثالي حتى 60 حرفًا، والوصف بين 120 و 160 حرفًا. عناوين صفحات الخدمات تُعدّل من داخل كل خدمة.',
                    'fields' => [
                        ['key' => 'seo_home_title', 'label' => 'الرئيسية — العنوان', 'type' => 'text', 'counter' => 60, 'default' => 'ابتكار وذوق للمقاولات | تشطيبات وديكور في الجبيل والأحساء'],
                        ['key' => 'seo_home_description', 'label' => 'الرئيسية — الوصف', 'type' => 'textarea', 'rows' => 2, 'counter' => 160, 'default' => 'ابتكار وذوق للمقاولات تقدم خدمات التشطيبات والتصميم الداخلي والديكور والأعمال الخشبية واللاندسكيب في الجبيل والأحساء بجودة تنفيذ دقيقة.'],
                        ['key' => 'seo_about_title', 'label' => 'من نحن — العنوان', 'type' => 'text', 'counter' => 60, 'default' => 'من نحن | ابتكار وذوق للمقاولات في الجبيل والأحساء'],
                        ['key' => 'seo_about_description', 'label' => 'من نحن — الوصف', 'type' => 'textarea', 'rows' => 2, 'counter' => 160, 'default' => 'تعرف على ابتكار وذوق للمقاولات وخبراتها ومنهجها في تنفيذ التشطيبات والتصميم الداخلي والديكور والأعمال الخشبية في الجبيل والأحساء.'],
                        ['key' => 'seo_services_title', 'label' => 'الخدمات — العنوان', 'type' => 'text', 'counter' => 60, 'default' => 'خدمات التشطيبات والديكور والأعمال الخشبية | ابتكار وذوق'],
                        ['key' => 'seo_services_description', 'label' => 'الخدمات — الوصف', 'type' => 'textarea', 'rows' => 2, 'counter' => 160, 'default' => 'استعرض خدمات ابتكار وذوق في التشطيبات والتصميم والديكور والأبواب والأعمال الخشبية وتنسيق الحدائق للمشاريع السكنية والتجارية.'],
                        ['key' => 'seo_documents_title', 'label' => 'الوثائق — العنوان', 'type' => 'text', 'counter' => 60, 'default' => 'الوثائق الرسمية | ابتكار وذوق للمقاولات'],
                        ['key' => 'seo_documents_description', 'label' => 'الوثائق — الوصف', 'type' => 'textarea', 'rows' => 2, 'counter' => 160, 'default' => 'اطلع على الوثائق الرسمية لابتكار وذوق للمقاولات، بما يشمل السجل التجاري وشهادات الالتزام والتوطين والضريبة والسلامة المهنية.'],
                        ['key' => 'seo_contact_title', 'label' => 'تواصل معنا — العنوان', 'type' => 'text', 'counter' => 60, 'default' => 'تواصل مع ابتكار وذوق للمقاولات | الجبيل والأحساء'],
                        ['key' => 'seo_contact_description', 'label' => 'تواصل معنا — الوصف', 'type' => 'textarea', 'rows' => 2, 'counter' => 160, 'default' => 'تواصل مع فروع ابتكار وذوق للمقاولات في الجبيل والأحساء لطلب خدمات التشطيبات والديكور والأعمال الخشبية واللاندسكيب وإدارة المشاريع.'],
                    ],
                ],
                [
                    'title' => 'أكواد التتبع (للمختصين)', 'icon' => 'fa-code',
                    'hint' => 'لا تغيّر هذه الحقول إلا بتوجيه من مسؤول التسويق.',
                    'fields' => [
                        ['key' => 'gtm_id', 'label' => 'معرّف Google Tag Manager', 'type' => 'text', 'default' => 'GTM-NSH8DLP6', 'half' => true, 'ltr' => true],
                        ['key' => 'google_verification', 'label' => 'كود توثيق Google Search Console', 'type' => 'text', 'default' => 'dW1hjvDFT6YLnEo3J61KVQ3OFisgBsLRkpBbisMzStw', 'half' => true, 'ltr' => true],
                        ['key' => 'custom_head_code', 'label' => 'كود إضافي داخل <head>', 'type' => 'code', 'default' => ''],
                    ],
                ],
            ],
        ],
    ],

    /* ============================== مجموعات العناصر المتكررة ============================== */
    'blocks' => [
        'about_feature' => [
            'title' => 'بطاقات المميزات — من نحن', 'icon' => 'fa-table-cells-large', 'page' => 'about', 'preview' => '/about',
            'singular' => 'بطاقة',
            'fields' => ['icon', 'title', 'text'],
        ],
        'about_card' => [
            'title' => 'الرؤية والرسالة ومراحل العمل', 'icon' => 'fa-bullseye', 'page' => 'about', 'preview' => '/about',
            'singular' => 'بطاقة',
            'fields' => ['icon', 'label', 'title', 'text'],
        ],
        'about_value' => [
            'title' => 'قيمنا — من نحن', 'icon' => 'fa-heart', 'page' => 'about', 'preview' => '/about',
            'singular' => 'قيمة',
            'fields' => ['icon', 'title'],
        ],
        'company_fact' => [
            'title' => 'بيانات السجل — صفحة الوثائق', 'icon' => 'fa-fingerprint', 'page' => 'documents_page', 'preview' => '/documents',
            'singular' => 'معلومة',
            'fields' => ['icon', 'label', 'title'],
            'labels' => ['label' => 'اسم المعلومة', 'title' => 'القيمة'],
        ],
    ],

    /* أيقونات شائعة تظهر في منتقي الأيقونات */
    'icons' => [
        'fa-trowel-bricks', 'fa-wand-magic-sparkles', 'fa-door-open', 'fa-seedling', 'fa-paint-roller', 'fa-brush', 'fa-palette',
        'fa-swatchbook', 'fa-ruler-combined', 'fa-compass-drafting', 'fa-layer-group', 'fa-border-all', 'fa-grip', 'fa-bolt',
        'fa-faucet-drip', 'fa-shield-halved', 'fa-umbrella', 'fa-industry', 'fa-hammer', 'fa-screwdriver-wrench', 'fa-toolbox',
        'fa-helmet-safety', 'fa-building', 'fa-house', 'fa-house-chimney', 'fa-couch', 'fa-chair', 'fa-bed', 'fa-lightbulb',
        'fa-tree', 'fa-leaf', 'fa-water', 'fa-droplet', 'fa-sun', 'fa-cubes', 'fa-cube', 'fa-gem', 'fa-award', 'fa-star',
        'fa-heart', 'fa-handshake', 'fa-crosshairs', 'fa-list-check', 'fa-clipboard-check', 'fa-eye', 'fa-bullseye', 'fa-route',
        'fa-magnifying-glass-chart', 'fa-location-dot', 'fa-phone', 'fa-envelope', 'fa-fingerprint', 'fa-tag', 'fa-circle-check',
        'fa-file-pdf', 'fa-file-contract', 'fa-certificate', 'fa-medal', 'fa-users', 'fa-user-tie', 'fa-clock', 'fa-calendar-check',
        'fa-truck', 'fa-warehouse', 'fa-city', 'fa-road', 'fa-fan', 'fa-fire', 'fa-snowflake', 'fa-plug', 'fa-wrench',
    ],
];
