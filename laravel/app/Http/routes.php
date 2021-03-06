<?php
// The three lines below enables CORS for client AJAX calls.
// The unit test will fail with errors about headers, just comment out the followings 3 lins
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

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
        return redirect('portal');
    });

});

// Ecofy: Unprotected routes

Route::group(['prefix' => 'auth'], function () {
    Route::get('signin', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationController@signin');
    Route::get('signup', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationController@signup');
    Route::get('signout', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationController@signout');

    Route::get('google', '\App\Ecofy\Modules\Auth\Controllers\AuthGoogleController@redirectToProvider');
    Route::get('google/callback', '\App\Ecofy\Modules\Auth\Controllers\AuthGoogleController@handleProviderCallback');

    Route::get('facebook', '\App\Ecofy\Modules\Auth\Controllers\AuthFacebookController@redirectToProvider');
    Route::get('facebook/callback', '\App\Ecofy\Modules\Auth\Controllers\AuthFacebookController@handleProviderCallback');

    Route::get('linkedin', '\App\Ecofy\Modules\Auth\Controllers\AuthLinkedInController@redirectToProvider');
    Route::get('linkedin/callback', '\App\Ecofy\Modules\Auth\Controllers\AuthLinkedInController@handleProviderCallback');
});

Route::post('api/signup', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationApiController@signup');
Route::post('api/signin', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationApiController@signin');
Route::post('api/signout', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationApiController@signout');

// Ecofy: Protected routes
Route::group(['middleware' => 'ecofyauth'], function () {
    Route::resource('api/accounts', '\App\Ecofy\Modules\Account\Controllers\AccountApiController');
    Route::resource('api/auths', '\App\Ecofy\Modules\Account\Controllers\AuthApiController');
    Route::get('api/myaccount', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationApiController@myaccount');

    Route::get('auth/subaccount', '\App\Ecofy\Modules\Auth\Controllers\AuthenticationController@subaccountForm');

    //Route::post('api/import', 'ImportApiController@process');

    Route::group(['prefix' => 'lms'], function () {
        // LMS'
        Route::get('assignment', '\App\EcoLearnia\Lms\Controllers\HomeController@assignment');
    });
});

// @todo include as part of protected API
Route::resource('api/accounts.relations', '\App\Ecofy\Modules\Relation\Controllers\RelationApiController');

//Route::group(['middleware' => 'cors'], function () {
    Route::resource('api/contents', '\App\EcoLearnia\Modules\Content\Controllers\ContentApiController');
    Route::resource('api/assignments', '\App\EcoLearnia\Modules\Assignment\Controllers\AssignmentApiController');

    Route::post('api/assignments/{assignmentUuid}/nextactivity', '\App\EcoLearnia\Modules\Assignment\Controllers\AssignmentApiController@nextActivity');
    Route::resource('api/assignments.activities', '\App\EcoLearnia\Modules\Assignment\Controllers\ActivityApiController');
    Route::put('api/assignments/{assignmentUuid}/activities/{activityUuid}/state', '\App\EcoLearnia\Modules\Assignment\Controllers\ActivityApiController@saveState');
    Route::post('api/assignments/{assignmentUuid}/activities/{activityUuid}/eval', '\App\EcoLearnia\Modules\Assignment\Controllers\ActivityApiController@evaluate');
//});

Route::group(['middleware' => 'ecofyauth:cont'], function () {
    Route::get('portal', '\App\EcoLearnia\Lms\Controllers\HomeController@portal');
});

// Temp
Route::get('page/{name}', 'HomeController@page');
