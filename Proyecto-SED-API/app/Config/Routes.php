<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/register', 'Home::register');

$routes->get('/login', 'Home::login');

$routes->get('/user', 'Home::user');

$routes->get('/edit-profile', 'Home::userEdit');

$routes->group('user', function ($routes) {
    $routes->post('create', 'UsuarioController::guardar');
    $routes->post('login', 'UsuarioController::login');
    $routes->get('id', 'UsuarioController::getuserById');
    $routes->post('edit', 'UsuarioController::editProfileUser');
});

$routes->group('admin', function ($routes) {
    $routes->post('create', 'AdminController::guardar');
});

$routes->group('super-admin', function ($routes) {
    $routes->post('create', 'SuperAdminController::guardar');
});

$routes->group('marcacion', function ($routes) {
    $routes->post('create', 'MarcacionController::guardar');
});
