<?php
use TCG\Voyager\Events\Routing;
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
/*Artisan Commands*/
 //Clear route cache:
 Route::group(['prefix'=>'commands'],function(){
    Route::get('/route-cache','CommandController@route');
    Route::get('/config-cache','CommandController@config');
    Route::get('/clear-cache','CommandController@clear');
    Route::get('/view-clear','CommandController@view');
    Route::get('/symlink','CommandController@symlink');
 });
 

Route::post('prueba/post', 'PruebaController@singleFile')->name('prueba.single');

Route::get('/','HomeController@index');

//Password Reset
Route::group(['prefix' => 'contrasena'], function() {
    Route::get('/olvido','Auth\CustomPasswordResetController@enviarToken')->name('token.email');
    Route::get('/olvido/{token}','Auth\CustomPasswordResetController@validarToken');
    Route::post('/generar','Auth\CustomPasswordResetController@actualizarContrasena')->name('generar.contrasena');
});
//Verify account
Route::group(['prefix' => 'email'], function() {
    Route::get('/verify', 'Auth\CustomVerificationController@show')->name('verification.notice');
    Route::get('/verify/{token}', 'Auth\CustomVerificationController@verify');
    Route::get('/resend', 'Auth\CustomVerificationController@resend')->name('verification.resend');
});



//Laravel Authentication
Auth::routes();
//Voyager Admin
Voyager::routes();
//Voyager login 
Route::group(['as' => 'voyager.'], function () {
    event(new Routing());
    $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';
    //register in admin
    Route::get('registro','HomeController@register')->name("register");
    Route::get('/ingreso', ['uses' => $namespacePrefix.'VoyagerAuthController@login',     'as' => 'login']);
    Route::post('/ingreso', ['uses' => $namespacePrefix.'VoyagerAuthController@postLogin', 'as' => 'postlogin']);
});