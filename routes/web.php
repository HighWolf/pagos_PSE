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

Route::get('/', function () {
    return view('welcome');
});

Route::get('transacciones', [
    'uses' => 'API\TransaccionesController@index',
    'as' => 'transacciones'
]);

Route::post('transacciones/informacionpago',[
    'uses' => 'API\TransaccionesController@informacionPago',
    'as' => 'transacciones.informacionpago'
]);

Route::post('transacciones/getTransactionRequest',[
    'uses' => 'API\TransaccionesController@getTransactionRequest',
    'as' => 'transacciones.getTransactionRequest'
]);

Route::get('transacciones/retorno/{reference}',[
    'uses' => 'API\TransaccionesController@getTransactionResult',
    'as' => 'transacciones.getTransactionResult'
]);

