<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'modules' => [
		'admin' => [
			'class' => 'app\modules\admin\Module'
		],
		'frontend' => [
			'class' => 'app\modules\frontend\Module'
		],
	],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ZeEZjw5laEgxDwetTSyLI8SIlaq-kELY',
            'parsers' => [
	            'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
	        'class' => 'yii\web\Response',
	        'on beforeSend' => function ($event){
		        $response = $event->sender;

		        if(isset($response->data['code'])){
			        unset($response->data['code']);
		        }

		        if(isset($response->data['name'])){
			        unset($response->data['name']);
		        }

		        $message = isset($response->data['message']) ? $response->data['message'] : $response->statusText;
		        $data = isset($response->data['data']) ? $response->data['data'] : $response->data;
		        $status = isset($response->data['status']) ? $response->data['status'] : $response->getStatusCode();

		        if($status === 404){
			        $data = '';
		        }

		        $response->data = [
			        'message' => $message,
			        'data' => $data,
			        'status' => $status
		        ];
	        },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
	            '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
