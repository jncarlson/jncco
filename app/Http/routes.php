<?php



Route::get('/calculate', 'HomeController@calculate');
Route::get('/', 'HomeController@index');
Route::post('/submit', 'HomeController@formSubmit');