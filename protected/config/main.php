<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Viper backend',
    // preloading 'log' component
    'language' => 'ru',
    'sourceLanguage' => 'ru',
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.bootstrap.BootstrapCButtonColumn',
        'application.extensions.bootstrap.BootstrapCLinkPager',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('*.*.*.*', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'loginUrl' => array('site/index'),
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'clientScript' => array(
            'scriptMap' => array(
                'jquery.js' => false,
                'jquery.min.js' => false,
                'jquery-ui.js' => false,
                'jquery-ui.min.js' => false,
            ),
        ),
        // database settings are configured in database.php
        'db' => array(
            'connectionString' => 'mysql:host=89.21.86.20;dbname=viper_dev',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Yte0nmtfkz',
            'charset' => 'utf8',
            'tablePrefix' => '',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
//                array(
//                    'class' => 'CWebLogRoute',
//                ),
            ),
        ),
    ),
    'params' => array(
        'dateFormat' => 'dd MMMM yyyy HH:mm',
        'adminEmail' => 'webmaster@example.com',
        'instagram_client_id' => 'eb616e0ab1da4912af809e4aea17bab6',
        'instagram_client_secret' => '1cc88f32d6504f4bb3022edc0b0a4b73',
        'instagram_apiCallback' => 'http://viper_backend.procreatlab.com/site/instagram',
        'vk_group_id' => 105022632,
        'vk_app_login_id' => 5142409,
        'vk_user_id' => 22575120,
        'vk_client_id' => 5146393,
        'vk_client_secret' => '7MVWKkuL3SxtakouMjeS',
        'vk_redirect_uri_access' => 'http://viper_backend.procreatlab.com/site/access',
        'vk_redirect_uri_login' => 'http://viper_backend.procreatlab.com/site/login',
    ),
);
