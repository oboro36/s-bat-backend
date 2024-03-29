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

Route::get('/getCertificate', function (Request $request) {
    return response()->json(['Data access allowed']);
});
//Video Search
Route::post('/getVideoSiteOption', 'Video\VideoSearchController@getVideoSiteOption');
Route::post('/getVideoProgramLineOption', 'Video\VideoSearchController@getVideoProgramLineOption');
Route::post('/getVideoProgramOption', 'Video\VideoSearchController@getVideoProgramOption');
Route::post('/getVideoLineOption', 'Video\VideoSearchController@getVideoLineOption');
Route::post('/getVideoContentOption', 'Video\VideoSearchController@getVideoContentOption');
Route::post('/getVideoAvailableDate', 'Video\VideoSearchController@getVideoAvailableDate');
Route::post('/getChamberList', 'Video\VideoSearchController@getChamberList');
Route::post('/getMasterMaintContents', 'Video\VideoSearchController@getMasterMaintContents');
Route::post('/getVideoData', 'Video\VideoSearchController@getVideoData');
