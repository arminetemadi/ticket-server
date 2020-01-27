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

Route::group(['prefix' => 'api'], function () use ($router) {
    // Auth
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');

    // User
    $router->put('users/update-profile', 'UserController@updateProfile');
    $router->get('users/{id}', 'UserController@get');
    Route::group(['middleware' => ['role:USERS_ADMIN']], function ($router) {
        $router->get('users', 'UserController@all');
        $router->put('users/update', 'UserController@update');
    });

    // Ticket
    $router->post('tickets', 'TicketController@create');
    $router->get('tickets/by-user', 'TicketController@getByUser');
    Route::group(['middleware' => ['role:TICKETS_ADMIN']], function ($router) {
        $router->get('tickets/all', 'TicketController@all');
        $router->get('tickets/{id}', 'TicketController@get');
        $router->post('tickets/reply', 'TicketController@reply');
    });
});
