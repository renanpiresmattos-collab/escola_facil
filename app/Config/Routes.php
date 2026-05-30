<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Web\Home::index');
// Dashboard route with authentication filter
$routes->get('dashboard', 'Web\Dashboard::index', ['filter' => 'auth']);
$routes->get('rup', 'Web\Rup::index', ['filter' => 'auth']);
$routes->get('rup/create', 'Web\Rup::create', ['filter' => 'auth']);
$routes->get('rup/(:num)/edit', 'Web\Rup::edit/$1', ['filter' => 'auth']);
$routes->get('logout', 'Web\Auth::logout');

$routes->group('api', static function ($routes) {
    $routes->post('login', 'Api\AuthController::login');
    $routes->post('logout', 'Api\AuthController::logout');
    $routes->get('usuarios', 'Api\UsuarioController::index', ['filter' => 'api-auth']);
    $routes->get('menu', 'Api\MenuController::index', ['filter' => 'api-auth']);
    $routes->get('rup', 'Api\RupController::index', ['filter' => 'api-auth']);
    $routes->get('rup/(:num)', 'Api\RupController::show/$1', ['filter' => 'api-auth']);
    $routes->post('rup', 'Api\RupController::store', ['filter' => 'api-auth']);
    $routes->put('rup/(:num)', 'Api\RupController::update/$1', ['filter' => 'api-auth']);
});
