<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$dotenv->required('YII_DEBUG')->allowedValues(['0', '1', 'true', 'false']);
$dotenv->required('YII_ENV')->allowedValues(['dev', 'prod', 'test']);
$dotenv->required(['YII_TRACE_LEVEL']);
$dotenv->required(['APP_NAME', 'APP_SUPPORT_EMAIL', 'APP_ADMIN_EMAIL']);
$dotenv->required(['DATABASE_DSN', 'DATABASE_USER', 'DATABASE_PASSWORD']);

$appVersion = trim((string) file_get_contents(__DIR__ . '/../version'));
putenv('APP_VERSION=' . $appVersion);
$_ENV['APP_VERSION'] = $appVersion;
