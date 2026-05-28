<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Web\Home::index');

$routes->group('api', static function ($routes) {
    $routes->post('login', 'Api\AuthController::login');
    $routes->post('logout', 'Api\AuthController::logout');
    $routes->get('usuarios', 'Api\UsuarioController::index', ['filter' => 'api-auth']);
});
