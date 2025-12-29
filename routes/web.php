<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/example', 'ExampleController@example');

/*
|--------------------------------------------------------------------------
| Swagger Routes
|--------------------------------------------------------------------------
*/
$router->get(config('l5-swagger.defaults.routes.docs'), [
    'as' => 'l5-swagger.default.docs',
    'middleware' => config('l5-swagger.defaults.routes.middleware.docs', []),
    'uses' => '\L5Swagger\Http\Controllers\SwaggerController@docs',
]);

$router->get(config('l5-swagger.documentations.default.routes.api'), [
    'as' => 'l5-swagger.default.api',
    'middleware' => config('l5-swagger.documentations.default.routes.middleware.api', []),
    'uses' => '\L5Swagger\Http\Controllers\SwaggerController@api',
]);

$router->get(config('l5-swagger.defaults.paths.swagger_ui_assets_path').'/{asset}', [
    'as' => 'l5-swagger.default.asset',
    'middleware' => config('l5-swagger.defaults.routes.middleware.asset', []),
    'uses' => '\L5Swagger\Http\Controllers\SwaggerController@asset',
]);
