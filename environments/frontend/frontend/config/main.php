<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'shop' => [
            'class' => 'app\modules\shop\Module',
        ],
        'geo_base' => [
            'class' => 'app\modules\geo_base\Module',
        ],
        'profile' => [
            'class' => 'app\modules\profile\Module',
        ],
        'rating' => [
            'class' => 'app\modules\rating\Module',
        ],
        'search' => [
            'class' => 'app\modules\search\Module',
        ],
        'broadcast' => [
            'class' => 'app\modules\broadcast\Module',
        ],
        'forms' => [
            'class' => 'app\modules\forms\Module'
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => '',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/sitemap.xml' => '/sitemap/index',
                '/profile/<action>' => '/profile/default/<action>',
                '/rating/<action>' => '/rating/default/<action>',
                '/search/<action>' => '/search/default/<action>',
                '/login' => '/site/login',
                '/logout' => '/site/logout',
                '/module/<module>/<action>' => '/<module>/default/<action>',
                '<url:brands>/<group:.+>' => '/site/catch',
                '<url:discount>/<group:.+>' => '/site/catch',
                '<url:.*>/filter/<filter:.+>' => '/site/catch',
                '<url:.*>' => '/site/catch',
            ]
        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
