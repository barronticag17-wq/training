<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('api/users', 'UserApi::index');
$routes->post('api/users', 'UserApi::create');
$routes->resource('api/users', ['controller' => 'UserApi']);
$routes->post('api/researches', 'ResearchApi::create');
$routes->get('api/researches', 'ResearchApi::index'); // You'll need this for fetching later
// Serve uploaded PDFs
$routes->get('uploads/researches/(:segment)', 'ResearchApi::serveFile/$1');
$routes->put('api/researches/(:num)/archive', 'ResearchApi::archive/$1');
$routes->post('api/auth/register', 'AuthApi::register');
$routes->post('api/auth/login', 'AuthApi::login');
$routes->get('api/researches/user/(:num)', 'ResearchController::getMySubmissions/$1');