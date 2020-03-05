<?php

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

use Illuminate\Http\Request;
use Illuminate\Http\Response;


$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->group(['prefix' => 'employees', 'middleware' => 'jwt.auth'], function () use ($router) {
        $router->get('getall', 'EmployeesController@getAll');
        $router->post('insert', 'EmployeesController@insert');
        $router->get('getbyid/{id}', 'EmployeesController@getEmployeeById');
        $router->post('update', 'EmployeesController@update');
        $router->delete('delete/{id}/{ownderId}', 'EmployeesController@delete');
        $router->get('restore/{id}', 'EmployeesController@restore');

    });

    $router->post('login', 'AuthController@authenticate');
});
