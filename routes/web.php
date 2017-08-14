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


$app->group(['prefix' => 'v1'], function () use ($app) {
	$app->get('user/{id}', 'UserController@get');

	$app->get('inventory/{steamid}', 'ItemController@getInventory');

	$app->group(['prefix' => 'items'], function () use ($app) {

	});

	//$app->get('user/{id}', 'SteamController@get');
});


$app->get('/', function () use ($app) {
    return $app->version();
});

