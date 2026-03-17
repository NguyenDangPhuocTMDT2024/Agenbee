<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();

define('ROOT_PATH', __DIR__);

foreach(glob(ROOT_PATH . '/config/*.php') as $configFile) {
    require_once $configFile;
}

foreach(glob(ROOT_PATH . '/core/mailer/*.php') as $mailerFile) {
    require_once $mailerFile;
}

foreach(glob(ROOT_PATH . '/core/*.php') as $coreFile) {
    require_once $coreFile;
}

foreach(glob(ROOT_PATH . '/app/models/*.php') as $modelFile) {
    require_once $modelFile;
}

foreach(glob(ROOT_PATH . '/app/controllers/*.php') as $controllerFile) {
    require_once $controllerFile;
}

$router = new Router();
foreach(glob(ROOT_PATH . '/routers/*.php') as $routerFile) {
    require_once $routerFile;
}

$projectName = '/agenbee';
$requestUri = str_replace($projectName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($requestUri, $method);
