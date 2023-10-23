<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('user', function ($routes) {
    $routes->post('create', 'UsuarioController::guardar');
});

$routes->group('admin', function ($routes) {
    $routes->post('create', 'AdminController::guardar');
});

$routes->group('super-admin', function ($routes) {
    $routes->post('create', 'SuperAdminController::guardar');
});
