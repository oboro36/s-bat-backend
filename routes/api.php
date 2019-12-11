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

Route::get('/test', function (Request $request) {
    return response()->json(['Laravel 6 CORS Example Test']);
});

Route::post('/getVideoSiteOption', 'Video\VideoSearchController@getVideoSiteOption');
Route::post('/getVideoOtherOption', 'Video\VideoSearchController@getVideoOtherOption');
Route::post('/getVideoData', 'Video\VideoSearchController@getVideoData');