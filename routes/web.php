<?php

use Illuminate\Support\Facades\Route;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->get('/version', function () use ($router) {
    return $router->app->version();
});

Route::group([

    'prefix' => 'api'

], function ($router) {
    
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('user-profile', 'AuthController@me');

    $router->post('attendance', 'AttendanceController@employeeAttendance');
});