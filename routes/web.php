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

    $router->group(['prefix' => 'employees', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->get('getall', 'EmployeesController@getAll');
        $router->post('insert', 'EmployeesController@insert');
        $router->get('getbyid/{id}', 'EmployeesController@getEmployeeById');
        $router->put('update', 'EmployeesController@update');
        $router->delete('delete/{id}/{ownerId}', 'EmployeesController@delete');
        $router->get('restore/{id}', 'EmployeesController@restore');
        $router->get('getbyname/{name}', 'EmployeesController@getEmployeeByName');
    });

    $router->post('login', 'AuthController@authenticate');

    $router->group(['prefix' => 'suppliers', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->get('getall', 'SuppliersController@getAll');
        $router->post('insert', 'SuppliersController@insert');
        $router->get('getbyid/{id}', 'SuppliersController@getSupplierById');
        $router->get('getbyname/{name}', 'SuppliersController@getSupplierByName');
        $router->put('update', 'SuppliersController@update');
        $router->delete('delete/{id}/{ownerId}', 'SuppliersController@delete');
        $router->get('restore/{id}', 'SuppliersController@restore');

    });

    $router->group(['prefix' => 'customers', 'middleware' => ['jwt.auth', 'only.cs']], function () use ($router) {
        $router->get('getall', 'CustomersController@getAll');
        $router->post('insert', 'CustomersController@insert');
        $router->get('getbyid/{id}', 'CustomersController@getCustomerById');
        $router->put('update', 'CustomersController@update');
        $router->delete('delete/{id}/{csId}', 'CustomersController@delete');
        $router->get('restore/{id}', 'CustomersController@restore');
        $router->get('getallpets/{id}', 'CustomersController@getAllCustomerPetsByCustomerId');
        $router->get('getbyname/{name}', 'CustomersController@getCustomerByName');
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
        $router->put('update', 'ServicesController@update');
        $router->get('getbyid/{id}', 'ServicesController@getServiceById');
        $router->get('getbyname/{serviceName}', 'ServicesController@getServiceByName');
        $router->get('getall', 'ServicesController@getAll');
    });

    $router->group(['prefix' => 'pets', 'middleware' => ['jwt.auth', 'only.cs']], function () use ($router) {
        $router->get('getall', 'PetsController@getAll');
        $router->post('insert', 'PetsController@insert');
        $router->delete('delete/{id}/{csId}', 'PetsController@delete');
        $router->get('restore/{id}', 'PetsController@restore');
        $router->put('update', 'PetsController@update');
        $router->get('getbyid/{id}', 'PetsController@getPetById');
        $router->get('getbyname/{name}', 'PetsController@getPetByName');
    });

    $router->group(['prefix' => 'pettypes', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->post('insert', 'PetTypesController@insert');
        $router->delete('delete/{id}/{ownerId}', 'PetTypesController@delete');
        $router->get('restore/{id}', 'PetTypesController@restore');
        $router->put('update', 'PetTypesController@update');
    });

    $router->group(['prefix' => 'petsizes', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->post('insert', 'PetSizesController@insert');
        $router->delete('delete/{id}/{ownerId}', 'PetSizesController@delete');
        $router->get('restore/{id}', 'PetSizesController@restore');
        $router->put('update', 'PetSizesController@update');
    });

    $router->group(['prefix' => 'noa/servicedetails'], function () use ($router) {
        $router->get('getall', 'ServiceDetailsController@getAll');
        $router->get('getbyid/{id}', 'ServiceDetailsController@getServiceDetailById');
    });

    $router->group(['prefix' => 'servicedetails', 'middleware' => ['jwt.auth', 'only.owner']], function () use ($router) {
        $router->post('insert', 'ServiceDetailsController@insert');
        $router->delete('delete/{id}', 'ServiceDetailsController@delete');
        $router->get('restore/{id}', 'ServiceDetailsController@restore');
        $router->put('update', 'ServiceDetailsController@update');
    });

    $router->group(['prefix' => 'uni', 'middleware' => ['jwt.auth']], function () use ($router) {
        $router->group(['prefix' => 'petsizes'], function () use ($router) {
            $router->get('getbysize/{size}', 'PetSizesController@getPetSizeBySize');
            $router->get('getbyid/{id}', 'PetSizesController@getPetSizeById');
            $router->get('getall', 'PetSizesController@getAll');
        });

        $router->group(['prefix' => 'pettypes'], function () use ($router) {
            $router->get('getbytype/{type}', 'PetTypesController@getPetTypeByType');
            $router->get('getbyid/{id}', 'PetTypesController@getPetTypeById');
            $router->get('getall', 'PetTypesController@getAll');
        });
    });
});
