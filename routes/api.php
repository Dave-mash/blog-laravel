<?php

use Illuminate\Http\Request;

use App\User;
use App\Car;

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


/*
Car routes
*/

// fetch all cars
Route::get('cars', 'CarController@index');

// fetch single car
Route::get('car/{id}', 'CarController@show');

// create new car
Route::post('car/{id}', 'CarController@store');

// update car
Route::put('car/{vendorId}/{carId}', 'CarController@update');

// delete a car
Route::delete('car/{vendorId}/{carId}', 'CarController@destroy');


/*
Cart routes
*/

// fetch cart
Route::get('cart', 'CartController@index');

// add to cart
Route::post('cart/{userId}/{carId}', 'CartController@store');

// delete cart
Route::delete('cart/{userId}/{cartId}', 'CartController@destroy');

// clear cart
Route::delete('cart/{userId}', 'CartController@clearCart');


/*
User routes
*/

// Post a user
Route::post('user', 'UserController@store');

// Log in a user
Route::post('user', 'UserController@login');

// Update a user
Route::put('user/{id}', 'UserController@update');

// Delete a user
Route::delete('user/{id}', 'UserController@destroy');
