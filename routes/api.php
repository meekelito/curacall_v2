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

Route::get('/cases/{status?}/{user_id}', 'Api\ApiController@getCases' ); 

Route::get('/case-preview', 'Api\ApiController@getCaseSpecific' ); 

Route::get('/case-participants', 'Api\ApiController@getParticipants' ); 

Route::get('/case-count', 'Api\ApiController@getCaseCount' ); 

Route::post('/case-accept', 'Api\ApiController@acceptCase' );  

Route::post('/case-close', 'Api\ApiController@closeCase' );  

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
});



