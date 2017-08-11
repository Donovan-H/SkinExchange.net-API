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

	$app->group(['prefix' => 'items'], function () use ($app) {
		//Categories
		$app->group(['prefix' => 'categories'], function () use ($app) {
			$app->get('/', 'ItemController@getCategories');
		});
		//Collections
		$app->group(['prefix' => 'collections'], function () use ($app) {
			$app->get('/', 'ItemController@getCollections');
			$app->get('search/{collection}', 'ItemController@getCollection');
			$app->get('{collection}', 'ItemController@getCollectionItems');
		});

		$app->get('{id}', 'ItemController@get');
	});

	//$app->get('user/{id}', 'SteamController@get');
});

$app->get('/', function () use ($app) {
    return $app->version();
});

