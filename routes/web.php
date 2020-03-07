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

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->group(['prefix' => 'employees', 'middleware' => 'jwt.auth'], function () use ($router) {
        $router->get('getall', 'EmployeesController@getAll');
        $router->post('insert', 'EmployeesController@insert');
        $router->get('getbyid/{id}', 'EmployeesController@getEmployeeById');
        $router->post('update', 'EmployeesController@update');
        $router->delete('delete/{id}/{ownerId}', 'EmployeesController@delete');
        $router->get('restore/{id}', 'EmployeesController@restore');

    });

    $router->post('login', 'AuthController@authenticate');

    $router->group(['prefix' => 'suppliers', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->get('getall', 'SuppliersController@getAll');
        $router->post('insert', 'SuppliersController@insert');
        $router->get('getbyid/{id}', 'SuppliersController@getSupplierById');
        $router->post('update', 'SuppliersController@update');
        $router->delete('delete/{id}/{ownerId}', 'SuppliersController@delete');
        $router->get('restore/{id}', 'SuppliersController@restore');

    });

    $router->group(['prefix' => 'customers', 'middleware' => 'jwt.auth'], function () use ($router) {
        $router->get('getall', 'CustomersController@getAll');
        $router->post('insert', 'CustomersController@insert');
        $router->get('getbyid/{id}', 'CustomersController@getCustomerById');
        $router->post('update', 'CustomersController@update');
        $router->delete('delete/{id}/{ownerId}', 'CustomersController@delete');
        $router->get('restore/{id}', 'CustomersController@restore');
        $router->get('getallpets/{id}', 'CustomersController@getAllCustomerPetsByCustomerId');

    });

    $router->group(['prefix' => 'products', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->post('insert', 'ProductsController@insert');
        $router->post('update', 'ProductsController@update');
        $router->delete('delete/{id}/{ownerId}', 'ProductsController@delete');
        $router->get('restore/{id}', 'ProductsController@restore');

    });

    $router->group(['prefix' => 'noa/products'], function () use ($router) {
        $router->get('getall', 'ProductsController@getAll');
        $router->get('getbyid/{id}', 'ProductsController@getProductById');
        $router->get('getbyname/{name}', 'ProductsController@getProductByName');
        $router->get('getimagebyid/{id}', ['as' => 'image_uri', 'uses' => 'ProductsController@getProductImageById']);
    });

    $router->group(['prefix' => 'services', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->post('insert', 'ServicesController@insert');
        $router->delete('delete/{id}/{ownerId}', 'ServicesController@delete');
        $router->get('restore/{id}', 'ServicesController@restore');
        $router->post('update', 'ServicesController@update');
    });


});
