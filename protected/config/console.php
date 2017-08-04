<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
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
    ),
    'components' => array(
        // database settings are configured in database.php
        // database settings are configured in database.php
        'db' => array(
            'connectionString' => 'mysql:host=89.21.86.20;dbname=viper_dev',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Yte0nmtfkz',
            'charset' => 'utf8',
            'tablePrefix' => '',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    'params' => array(
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
