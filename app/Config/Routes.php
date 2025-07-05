<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Game routes
$routes->get('/game', 'Game::index');
$routes->post('/game/check', 'Game::check');
$routes->get('/game/leaderboard', 'Game::leaderboard');
$routes->get('/game/treasures', 'Game::treasures');
$routes->get('/game/treasure/(:num)/leaderboard', 'Game::treasureLeaderboard/$1');
$routes->get('/game/my-progress', 'Game::myProgress');
$routes->post('/game/reset', 'Game::reset');
$routes->get('/game/test', 'Game::test');

// Authentication routes
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/profile', 'Auth::profile');
$routes->post('/auth/profile/update', 'Auth::updateProfile');

// Add options method for CORS
$routes->options('/game/check', 'Game::options');
