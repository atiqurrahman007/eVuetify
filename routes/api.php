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

Route::group([
'prefix' => 'auth'
], function () {
Route::post('login', 'AuthController@login');
Route::post('signup', 'AuthController@signup');

Route::group(['middleware' => 'auth:api'], function() {

Route::get('logout', 'AuthController@logout');
Route::get('user', 'AuthController@user');
Route::get('/user/profile', 'AuthController@profile');

});


});

Route::group(['middleware'=>['auth:api'], 'namespace'=>'Api'], function(){
   // Backend
   Route::apiResource('role','RoleController');
   Route::apiResource('users','UsersController');
  //  Administration
  Route::apiResource('administration', 'AdministrationController');
   Route::put('/users/role/{id}','UsersController@updateRole');
   Route::post('/users/profile','UsersController@profileImage');
   Route::apiResource('tag','TagController');
   Route::get('/tag/status/{id}','TagController@status');
   Route::post('/tag/multidelete','TagController@deleteAll');
   Route::post('/email/verify','UsersController@emailCheck');
  //    Category 
  Route::apiResource('category', 'CategoryController');
  Route::put('category/statusupdate/{id}', 'CategoryController@updateStatus');
  Route::post('category/deleteall', 'CategoryController@deleteAll');
  // Brand 
  Route::apiResource('brand', 'BrandController');
  Route::put('brand/statusupdate/{id}', 'BrandController@status');
  Route::post('brand/deleteall/', 'BrandController@deleteMultiple');

  
});

// Daily Product 
Route::apiResource('dailyproduct', 'Api\DailyproductController');