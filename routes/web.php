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

Route::get('/', function (){return view('welcome');})->name('home');


//CAS
Route::get('/login', 'UnauthenticatedController@login')->name('cas.login');
Route::get('/logout', 'UnauthenticatedController@logout')->name('cas.logout');

//Participants
Route::get('/account', 'ParticipantController@index')->name('participant.home');
Route::get('/user/profile', 'ParticipantController@showProfile')->name('profile');
Route::get('/account/logout', 'ParticipantController@logout')->name('participant.logout');
Route::post('/account/validateCard', 'ParticipantController@validateCard')->name('participant.validateCard');
Route::get('/account/charge', 'ParticipantController@charge')->name('participant.charge');
Route::get('/account/afterCharge', 'ParticipantController@afterCharge')->name('participant.afterCharge');
Route::get('/account/logout', 'ParticipantController@logout')->name('participant.logout');

//OC
Route::get('/godmode', 'OCController@index')->name('oc.home');
Route::get('/godmode/logout', 'OCController@logout')->name('oc.logout');

//Misc
Route::get('/terms', 'MiscController@terms')->name('terms');

//Test
Route::get('/test', 'ParticipantController@test')->name('participant.test');

