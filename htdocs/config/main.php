<?php

$config = [
    'id' => 'app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@frontmedia' => '/media',
        '@admin-views' => '@app/modules/admin/views',
        '@bower' => dirname(__DIR__) . '/vendor/bower-asset',
        '@npm'   => dirname(__DIR__) . '/vendor/npm-asset',
    ],
    'components' => [
        'assetManager' => [
            'forceCopy' => false, // Note: May degrade performance with Docker or VMs
            'linkAssets' => false, // Note: May also publish files, which are excluded in an asset bundle
            'dirMode' => YII_ENV_PROD ? 0777 : null, // Note: For using mounted volumes or shared folders
            'bundles' => YII_ENV_PROD ? require(__DIR__ . '/assets-prod.php') : null,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DATABASE_DSN'),
            'username' => getenv('DATABASE_USER'),
            'password' => getenv('DATABASE_PASSWORD'),
            'charset' => 'utf8',
            'tablePrefix' => getenv('DATABASE_TABLE_PREFIX'),
        ],
        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'useFileTransport' => YII_ENV_PROD ? false : true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => getenv('APP_PRETTY_URLS') ? true : false,
            'showScriptName' => getenv('YII_ENV_TEST') ? true : false,
            'rules' => [
                'docs/<file:[a-zA-Z0-9_\-\.]*>' => 'docs',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@yii/gii/views/layouts' => '@admin-views/layouts',
                ],
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => '@admin-views/layouts/main',
        ],
        'datecontrol' => [
            'class' => '\kartik\datecontrol\Module'
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            'downloadAction' => 'gridview/export/download',
        //'i18n' => [],           
        ],
        /* 'docs'    => [
          'class'  => \schmunk42\markdocs\Module::class,
          'layout' => '@app/views/layouts/container',
          ], */
        /* 'packaii' => [
          'class'  => \schmunk42\packaii\Module::class,
          'layout' => '@admin-views/layouts/main',
          ], */
        'user' => [
            'class' => 'Da\User\Module',
            'layout' => '@admin-views/layouts/main',
            'defaultRoute' => 'profile',
            'administrators' => ['admin'],
        ],
    ],
    'params' => [
        'appName' => getenv('APP_NAME'),
        'adminEmail' => getenv('APP_ADMIN_EMAIL'),
        'supportEmail' => getenv('APP_SUPPORT_EMAIL'),
        'yii.migrations' => [
            '@vendor/2amigos/yii2-usuario/src/User/Migration',
        ]
    ]
];


$web = [
    'components' => [
        'log' => [
            'traceLevel' => getenv('YII_TRACE_LEVEL'),
            'targets' => [
                // log route handled by nginx process
                [
                    'class' => 'dmstr\log\SyslogTarget',
                    'prefix' => function () {
                        return '';
                    },
                    'levels' => YII_DEBUG ? ['error', 'warning', 'info'] : ['error', 'warning'],
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION'],
                    'enabled' => YII_DEBUG ? true : false,
                ],
                // standard file log route
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => YII_DEBUG ? ['error', 'warning', 'info'] : ['error', 'warning'],
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION'],
                    'logFile' => '@app/runtime/logs/web.log',
                    'dirMode' => 0777
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => getenv('APP_COOKIE_VALIDATION_KEY'),
        ],
        'user' => [
            'identityClass' => 'Da\User\Model\User',
        ],
    ]
];


$console = [
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => ['@app/migrations'],
            'migrationNamespaces' => ['Da\User\Migration'],
        ],
    ],
    'components' => [
        'log' => [
            'traceLevel' => getenv('YII_TRACE_LEVEL'),
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'prefix' => function () {
                        return '[console]';
                    },
                    'levels' => YII_DEBUG ? ['error', 'warning', 'info'] : ['error', 'warning'],
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION'],
                    'logFile' => '@app/runtime/logs/console.log',
                    'dirMode' => 0777
                ],
            ],
        ],
    ]
];


$allowedIPs = [
    '127.0.0.1',
    '::1',
    '192.168.*',
    '172.17.*'
];

if (php_sapi_name() == 'cli') {
    // Console application
    $config = \yii\helpers\ArrayHelper::merge($config, $console);
} else {
    // Web application
    if (YII_ENV_DEV) {
        // configuration adjustments for web 'dev' environment
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = [
            'class' => 'yii\debug\Module',
            'allowedIPs' => $allowedIPs
        ];
    }
    $config = \yii\helpers\ArrayHelper::merge($config, $web);
}

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => $allowedIPs
    ];
}

if (file_exists(__DIR__ . '/local.php')) {
    // Local configuration, if available
    $local = require(__DIR__ . '/local.php');
    $config = \yii\helpers\ArrayHelper::merge($config, $local);
}

return $config;
