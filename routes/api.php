<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataCrypter;
use App\Http\Controllers\BlogController;


Route::middleware('login')->group(function (){
    /**
     * Blog Test Routes
     */
    Route::post('encode',[DataCrypter::class,'crypter']);
    Route::post('decode',[DataCrypter::class,'crypter']);
    Route::post('test',function (){ return "basarili"; });
    Route::get('migrate',function(){
        Artisan::call('migrate:refresh');
        dd('migrated!');
    });
    Route::get('reboot',function(){
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('key:generate');
    });


    /**
     * Blog Admin ROUTES
    */
    Route::post('create-post',[BlogController::class,'createPost']);
    Route::post('create-recommended',[BlogController::class,'createRecommended']);

});


Route::post('login',[ApiController::class,'login']);

Route::post('main-info',[BlogController::class,'getMainInfo']);

Route::post('about-us',[BlogController::class,'getAboutUs']);

Route::post('send-contact',[BlogController::class,'sendContact']);

Route::post('send-comment',[BlogController::class,'sendComment']);
