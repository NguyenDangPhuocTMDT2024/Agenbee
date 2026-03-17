<?php
//Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');

$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

$router->get('/logout', 'AuthController@logout');

$router->get('/forgot', 'AuthController@showForgot');
$router->post('/forgot', 'AuthController@forgot');

$router->get('/reset', 'AuthController@showReset');
$router->post('/reset', 'AuthController@reset');

$router->get('/active', 'AuthController@showActive');

$router->post('/logout', 'AuthController@logout');
//User routes
$router->get('/', 'UserController@home');
$router->get('/home', 'UserController@home');

//admin routes
$router->get('/admin/', 'AdminController@dashboard');

$router->get('/admin/package', 'AdminController@package');

$router->get('/admin/package/create', 'AdminController@showPackageCreate');
$router->post('/admin/package/create', 'AdminController@packageCreate');

$router->get('/admin/package/edit', 'AdminController@showPackageEdit');
$router->post('/admin/package/edit', 'AdminController@packageEdit');

$router->get('/admin/package/delete', 'AdminController@packageDelete');

$router->get('/admin/profile', 'AdminController@showProfile');

