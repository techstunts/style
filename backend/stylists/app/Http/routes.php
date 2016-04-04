<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('auth/{action}', 'Auth\AuthController@index');
Route::any('password/{action}/{token?}', 'Auth\PasswordController@index');

//Called by istyleyouapi. This rule should be deleted once old apis migrate to lumen
Route::get('job/{action}/{id}', 'JobController@index');

Route::group(['middleware' => 'auth'], function(){

    Route::any('client/{action}/{id?}/{action_id?}', 'ClientController@index');

    Route::any('product/{action}/{id?}', 'ProductController@index');

    Route::any('look/{action}/{id?}/{action_id?}', 'LookController@index');

    Route::any('merchant/product/{action}/{id?}', 'Merchant\ProductController@index');

    Route::any('stylist/{action}/{id?}', 'StylistController@index');

    Route::any('collection/{action}/{id?}/{action_id?}', 'CollectionController@index');

    Route::any('requests/{action}/{id?}/', 'StyleRequestsController@index');

    Route::any('recommendation/{action}/{id?}/', 'RecommendationController@index');

    Route::get('report/{report_id}', 'ReportController@index');

    Route::get('report/{report_id}/query', 'ReportController@query');
});

