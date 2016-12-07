<?php
return [
    'domainName' => 'site.ru',
    'hostname' => 'http://site.ru',
    'adminEmail' => 'info@site.ru',
    'supportEmail' => 'info@site.ru',
    'user.passwordResetTokenExpire' => 3600,
    'shop' => [
        'phpAuthUser' => 'admin',
        'phpAuthPw' => 'p7Nr5Bzu',
        'fileLimit' => 10485760,
        'uploadDir' => '/web/uploads/shop',
        /*
         * Путь к группе в XML, с которой начинается загрузка в базу сайта
         */
        'startGroupPath' => '/КоммерческаяИнформация/Классификатор/Группы/Группа',
        /*
         * Родительский URL. Страница с данным URL должна быть создана.
         */
        'catalogUrl' => '/catalog',
        /*
         * Шаблон для префикса URL загружаемого каталога [/catalog/{level-1}/{level-2}/.../{level-n}] к которому добавляется содержимое переменной $link->name
         */
        'groupUrlPrefix' => '/{level-2}/{level-3}',
        'goodUrlPrefix' => '/product',
        'categoriesId' => 3,
        'group_layouts_id' => 1,
        'good_layouts_id' => 1,
        'goods_views_id' => 2,
        'good_views_id' => 3,
        'gallery' => [
            'good' => 4,
            'group' => 9,
            'color' => 5,
            'Pogonazh' => 6,
            'other' => 7,
            'logo' => 8
        ],
        'galleryLink' => [4, 9],
        'hitShopProperty' => ['id' => 9, 'value' => 'Беларусь'],
        'promoShopProperty' => ['id' => 2, 'value' => 'Gesser'],
    ]
];
