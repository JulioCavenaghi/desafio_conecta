<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('api', ['namespace' => 'App\Controllers\Api', 'filter' => 'apiauth'], function ($routes) {
    $routes->get('users', 'Users::index');

    $routes->get('users/active', 'Users::active');
    
    $routes->post('users', 'Users::create');
    
    $routes->get('users/(:num)', 'Users::show/$1');
    
    $routes->put('users/(:num)', 'Users::update/$1');
    $routes->patch('users/(:num)', 'Users::update/$1');
    
    $routes->delete('users/(:num)', 'Users::delete/$1');
});