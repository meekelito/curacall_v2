<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResources([
    'case' => 'Api\CaseController',
    'messages' => 'Api\MessageController',
    'contacts' => 'Api\UserController'
]);


Route::post('login', 'Api\AuthController@login');
Route::post('logout', 'Api\AuthController@logout');

Route::group([
    'middleware' => 'jwt.auth',
], function ($router) {
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('me', 'Api\AuthController@me');
});

Route::middleware('jwt.auth')->get('users', function () {
    return auth('api')->user();
});


Route::get('/cases/{status?}/{user_id}', 'Api\ApiController@getCases' ); 

Route::get('/case-preview', 'Api\ApiController@getCaseSpecific' ); 

Route::get('/case-participants', 'Api\ApiController@getParticipants' ); 

Route::get('/case-count', 'Api\ApiController@getCaseCount' ); 

Route::post('/case-accept', 'Api\ApiController@acceptCase' );  

Route::post('/case-close', 'Api\ApiController@closeCase' ); 

 Route::post('/case-new', 'Api\ApiController@newCase' );  


Route::post('/case-test', 'Api\ApiController@testCase' );  

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
});



