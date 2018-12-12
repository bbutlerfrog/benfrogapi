<?php

use App\Http\Controllers\EmployeeController;

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

$router->post('/auth/login', 'AuthController@postLogin');
$router->group(['middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('/employees', 'EmployeeController@show');
});



