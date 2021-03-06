<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
Yii::setAlias('@tests', __DIR__);
new \yii\console\Application([
        'id' => 'unit',
        'basePath' => __DIR__,
        'vendorPath' => __DIR__ . '/../vendor',
        'components' => [
            'assetManager' => [
                'class' => 'tests\AssetManager',
                'basePath' => '@tests/assets',
                'baseUrl' => '/',
            ],
            'session' => [
                'class' => 'tests\Session',
            ],
        ],
    ]);