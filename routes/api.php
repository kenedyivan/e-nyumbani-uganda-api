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

Route::get('/test', function () {
    return "ok";
});

Route::get('/listings', 'MobileUser\ListingsController@getAllListings');
Route::get('/listings/for-rent', 'MobileUser\ListingsController@getAllForRent');
Route::get('/listings/for-sale', 'MobileUser\ListingsController@getAllForSale');
Route::get('/listings/search', 'MobileUser\ListingsController@search');
Route::get('/listings/property', 'MobileUser\ListingsController@showProperty');
Route::post('/listings/property/upload-photo', 'MobileUser\ListingsController@uploadPhoto');
Route::post('/listings/property/update-photo', 'MobileUser\ListingsController@updatePhoto');
Route::post('/listings/property/create', 'MobileUser\ListingsController@createProperty');
Route::get('/listings/property/cloud-uploader', 'MobileUser\ListingsController@cloudinaryUploader');

//Update property
Route::get('/listings/property/edit','MobileUser\ListingsController@editProperty');
Route::post('/listings/property/save','MobileUser\ListingsController@save');


//User Authentication routes
Route::post('/agent/login', 'MobileUser\LoginController@login');
Route::post('/agent/profile', 'MobileUser\LoginController@userProfile');

//User social authentication routes
Route::post('/agent/login-social-user', 'MobileUser\LoginController@socialLogin');

//User registration
Route::post('/agent/register', 'MobileUser\RegisterController@register');
Route::post('/agent/other-details', 'MobileUser\RegisterController@createOtherDetails');

//Agent properties
Route::get('/agent/my-properties', 'MobileUser\AgentPropertiesController@myProperties');
Route::get('/agent/my-property', 'MobileUser\AgentPropertiesController@myProperty');
Route::get('/agent/properties', 'MobileUser\AgentPropertiesController@agentProperties');

//Agent
Route::get('/agents', 'MobileUser\AgentsController@getAgents');
Route::get('/agent', 'MobileUser\AgentsController@showAgent');
Route::get('/agent/account-details', 'MobileUser\AgentsController@accountDetails');

//Update agent details
Route::post('/agent/update-profile', 'MobileUser\AgentsController@updateProfile');
Route::post('/agent/update-contact', 'MobileUser\AgentsController@updateContact');
Route::post('/agent/update-company', 'MobileUser\AgentsController@updateCompany');
Route::post('/agent/upload-profile-picture', 'MobileUser\AgentsController@uploadProfilePicture');

//Property review
Route::post('/review', 'MobileUser\PropertyReviewsController@reviewProperty');

//Add to favorites routes
Route::get('/add-to-favorites', 'MobileUser\AddToFavoritesController@addToFavorites');

//Get agent favorites
Route::get('/my-favorites', 'MobileUser\AddToFavoritesController@getAgentFavourites');

Route::get('/listings/types', 'MobileUser\ListingsController@propertyTypes');

Route::get('/listings/trait', 'MobileUser\ListingsController@tryTrait');

