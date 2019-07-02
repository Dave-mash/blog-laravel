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

// fetch all cars
Route::get('cars', 'CarController@index');

// fetch single car
Route::get('car/{id}', 'CarController@show');

// create new car
Route::post('car/{id}', 'CarController@store');

// update car
Route::put('car/{id}', 'CarController@update');

// delete a car
Route::delete('car/{id}', 'CarController@destroy');

