<?php
return [
    'domainName' => 'site.ru',
    'hostname' => 'http://site.ru',
    'adminEmail' => 'info@site.ru',
    'supportEmail' => 'info@site.ru',
    'companyName' => 'ООО "Наименование"',
    'user.passwordResetTokenExpire' => 3600,
    'monthsParentCase' => ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
    'rating' => ['max' => 5],
    'uploadURL' => '/assets/upload',
    'uploadDir' => Yii::getAlias('@frontend/web/assets/upload'),
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
        'groupLayoutsId' => 1,
        'productLayoutsId' => 1,
        'groupViewsId' => 2,
        'productViewsId' => 3,
        'gallery' => [
            'good' => 4,
            'group' => 9,
            'color' => 5,
            'Pogonazh' => 6,
            'other' => 7,
            'logo' => 8
        ],
        'propertyGalleryType' => 'brands',
        'propertyGallery' => [
            'Производитель' => 17827,
            'Бренд' => 17827,
        ],
        'galleryLink' => [4, 9],
        'hitShopProperty' => ['id' => 23, 'value' => '1'],
        'promoShopProperty' => ['id' => 22, 'value' => '1'],
        'promoProductsId' => [
            467 => [17513, 17103],
        ],
    ],
    'broadcast' => [
        'upload' => '/uploads/broadcast',
        'clearMngUrl' => '/mng',
    ],
];
