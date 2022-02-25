<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagsController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth'],function(){
    Route::resource('/platforms', PlatformController::class);
    Route::resource('/services', ServiceController::class);
    Route::get('/servers/service/{sid}',[ServerController::class,"ServiceServers"]);
    Route::get('/servers/service/{sid}/add',[ServerController::class,'AddServerForService']);
    Route::resource('/servers',ServerController::class);
    Route::resource('/settings', SettingsController::class);
    Route::resource('/properties',PropertyController::class);
    Route::resource('/tags',TagsController::class);
    Route::get('/',[MainController::class,'index']);
    Route::get('/home', [HomeController::class,'index'])->name('home');
    Route::get('/devices' , [DeviceController::class,'index']);
    Route::post('/devices' , [DeviceController::class,'search']);
    Route::delete('/devices/{id}' , [DeviceController::class,'delete']);

    Route::patch('/groups/{id}',[GroupController::class,'update']);
    Route::post('/groups',[GroupController::class,'create']);
    Route::get('/groups',[GroupController::class,'index']);
    Route::delete('/groups/{id}',[GroupController::class,'destroy']);
});


Route::get('/home', [HomeController::class,'index'])->name('home');
require __DIR__.'/auth.php';
