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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test',function(){
    return "ok";
});

Route::get('/listings','MobileUser\ListingsController@getAllListings');
Route::get('/listings/for-rent','MobileUser\ListingsController@getAllForRent');
Route::get('/listings/for-sale','MobileUser\ListingsController@getAllForSale');
Route::get('/listings/search','MobileUser\ListingsController@search');
Route::get('/listings/property','MobileUser\ListingsController@showProperty');
Route::post('/listings/property/upload-photo','MobileUser\ListingsController@uploadPhoto');
Route::post('/listings/property/create','MobileUser\ListingsController@createProperty');
Route::get('/listings/property/cloud-uploader', 'MobileUser\ListingsController@cloudinaryUploader');


//User Authentication routes
Route::post('/login','MobileUser\LoginController@login');
Route::post('/profile','MobileUser\LoginController@userProfile');

//User social authentication routes
Route::post('/login-social-user','MobileUser\LoginController@socialLogin');

//User registration
Route::post('/register','MobileUser\RegisterController@register');

//Agent properties
Route::get('/agent/my-properties','MobileUser\AgentPropertiesController@myProperties');
Route::get('/agent/properties','MobileUser\AgentPropertiesController@agentProperties');

//Agent
Route::get('/agents', 'MobileUser\AgentsController@getAgents');