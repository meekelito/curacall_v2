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
  Route::post('login', 'Api\UserController@login');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('login_mobile', 'Api\AuthController@login');
Route::post('check_email', 'Api\AuthController@check_email');
Route::post('forgot/password', 'Api\Auth\ForgotPasswordController')->name('forgot.password');
Route::apiResource('support', 'Api\SupportTicketController');

Route::group([
    'middleware' => 'jwt.auth',
], function ($router) {
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('me', 'Api\AuthController@me');
    Route::put('contact/password/{id}', 'Api\UserController@password_update');
    Route::put('contact/pin/{id}', 'Api\UserController@pin_update');
    Route::put('contact/alert/{id}', 'Api\UserController@update_alert');
    Route::get('contact/search', 'Api\UserController@search');
    Route::post('case-reopen', 'Api\CaseController@reopen_case');
    Route::post('case-read/{id}', 'Api\CaseController@read_case');
    Route::post('case-note/{id}', 'Api\CaseController@add_note');
    Route::post('case-forward/{id}', 'Api\CaseController@forward');
    Route::post('/report/average','Api\ApiController@getReportAverageTime');
    Route::get('message/recent','Api\MessageController@recent');
    Route::post('message/create/room','Api\MessageController@create_room');
    Route::post('message/delete/all','Api\RoomDeleteMessageController@store');

    Route::apiResources([
        'case' => 'Api\CaseController',
        'messages' => 'Api\MessageController',
        'contacts' => 'Api\UserController'
    ]);

});

// Route::middleware('jwt.auth')->get('users', function () {
//     return auth('api')->user();
// });


Route::get('/cases/{status?}/{user_id}', 'Api\ApiController@getCases' ); 

    Route::group([
        'middleware' => 'jwt.auth',
    ], function ($router) {
        
        Route::post('/cases', 'Api\ApiController@getCases' ); 

        Route::get('/case-preview', 'Api\ApiController@getCaseSpecific' ); 

        Route::get('/case-participants', 'Api\ApiController@getParticipants' ); 

        Route::get('/case-count', 'Api\ApiController@getCaseCount' ); 

        Route::post('/case-accept', 'Api\ApiController@acceptCase' );  

        Route::post('/case-close', 'Api\ApiController@closeCase' ); 

        Route::post('/case-new', 'Api\ApiController@newCase' );  

        Route::post('/case-test', 'Api\ApiController@testCase' ); 

        Route::post('/integration/dynamics/send-case-to-oncall', 'Api\ApiController@sendCaseOncall' ); 

        Route::post('/integration/dynamics/add-oncall-backup', 'Api\ApiController@addOnCallBackUp' ); 

        

        //mobile app
        Route::post('forward-case', 'Api\ApiController@forwardCase');
    });

Route::post('notification/remind', 'Api\ApiController@reminderNotification');
// Route::fallback(function(){ 
//     return response()->json([
//         'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
// });
Route::middleware('jwt.auth')->get('users', function () {
    return auth('api')->user();
});


Route::get('test', 'Api\ApiController@testcron');


// Route::fallback(function(){
//     return response()->json([
//         'message' => 'Page Not Found. If error persists, contact info@curacall.com'], 404);
// });









