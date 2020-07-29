<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post("/delete_command", "MainController@deleteCommand");
Route::post("/delete_text", "MainController@deleteText");
Route::post("/save_new_command", "MainController@saveCommand");
Route::post("/save_text", "MainController@saveText");
Route::post("/get_text", "MainController@getText");



Route::get("/create_default_tables", "MainController@createTables");
