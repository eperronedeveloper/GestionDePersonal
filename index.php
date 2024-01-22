<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

define('PROJECT_DIR', realpath(__DIR__));
define('VENDOR_DIR', PROJECT_DIR . '/vendor');
define('APP_DIR', PROJECT_DIR . '/application');
define('MIDDLEWARE_DIR', PROJECT_DIR . '/middleware');
define('MODELS_DIR', PROJECT_DIR . '/models');
define('CONTROLLERS_DIR', PROJECT_DIR . '/controllers');
define('LIBS_DIR', PROJECT_DIR . '/libs');
define('DEVLOG_DIR', PROJECT_DIR . '/log');

/* TIPOS DE CONEXION A DB */
define('MYSQL', 'mysql');
define('POSTGRES', 'postgres');

require PROJECT_DIR . '/vendor/autoload.php';
session_start();

// Instantiate the application
$settings = require PROJECT_DIR . '/application/Settings.php';

$app = new \Slim\App($settings);

// cargar archivos application
foreach (glob(PROJECT_DIR . "/application/*.php") as $archivosbase) {
    require_once $archivosbase;
}

// Register routes
require PROJECT_DIR . '/routes/routes.php';

foreach (glob(PROJECT_DIR . "/routes/routes.*.php") as $enrutador) {
    require_once $enrutador;
}

foreach (glob(PROJECT_DIR . "/controllers/*.php") as $controllers) {
    require_once $controllers;
}

foreach (glob(PROJECT_DIR . "/models/*.php") as $models) {
    require_once $models;
}

foreach (glob(PROJECT_DIR . "/commons/*.php") as $archivosCommons) {
    require $archivosCommons;
}

// Run!
$app->run();