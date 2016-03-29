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

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    });

});

// Ecofy: Unprotected routes
Route::get('auth/google', '\App\Ecofy\Modules\Auth\Controllers\AuthGoogleController@redirectToProvider');
Route::get('auth/google/callback', '\App\Ecofy\Modules\Auth\Controllers\AuthGoogleController@handleProviderCallback');

Route::get('auth/facebook', '\App\Ecofy\Modules\Auth\Controllers\AuthFacebookController@redirectToProvider');
Route::get('auth/facebook/callback', '\App\Ecofy\Modules\Auth\Controllers\AuthFacebookController@handleProviderCallback');

Route::get('auth/linkedin', '\App\Ecofy\Modules\Auth\Controllers\AuthLinkedInController@redirectToProvider');
Route::get('auth/linkedin/callback', '\App\Ecofy\Modules\Auth\Controllers\AuthLinkedInController@handleProviderCallback');

Route::post('api/signup', '\App\Ecofy\Modules\Auth\Controllers\AuthApiController@signup');
Route::post('api/signin', '\App\Ecofy\Modules\Auth\Controllers\AuthApiController@signin');
Route::post('api/signout', '\App\Ecofy\Modules\Auth\Controllers\AuthApiController@signout');

// Ecofy: Protected routes
Route::group(['middleware' => 'ecofyauth'], function () {
    Route::resource('api/accounts', '\App\Ecofy\Modules\Account\Controllers\AccountApiController');
    Route::resource('api/auths', '\App\Ecofy\Modules\Account\Controllers\AuthApiController');
    Route::get('api/myaccount', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationApiController@myaccount');

    //Route::post('api/import', 'ImportApiController@process');
});
// @todo include as part of protected API
Route::resource('api/accounts.relations', '\App\Ecofy\Modules\Relation\Controllers\RelationApiController');
