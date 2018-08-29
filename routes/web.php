<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontController@index')->name('front');
Route::post('/', 'FrontController@store');
Route::post('/process/{id}', 'FrontController@processing');

Route::get('/sites', 'ParseSitesController@index')->name('sites');
Route::get('/sites/{id}', 'ParseSitesController@show');
Route::delete('/sites/delete/{id}', 'ParseSitesController@destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
