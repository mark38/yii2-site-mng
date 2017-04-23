<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => 'app\controllers\MigrateController',
        ],
    ],
    'modules' => [
        'shop' => [
            'class' => 'app\modules\shop\Module',
        ],
        'broadcast' => [
            'class' => 'app\modules\broadcast\Module',
        ],
        'certificates' => [
            'class' => 'app\modules\certificates\Module',
        ],
        'ftptransfer' => [
            'class' => 'app\modules\ftptransfer\Module',
        ],
        'ipGeoBase' => [
            'class' => 'app\modules\ipGeoBase\Module',
        ],
        'sms' => [
            'class' => 'app\modules\sms\Module',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
