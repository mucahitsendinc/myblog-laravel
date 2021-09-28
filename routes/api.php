<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataCrypter;
use App\Http\Controllers\BlogController;


Route::middleware('login')->group(function (){
    Route::post('encode',[DataCrypter::class,'crypter']);
    Route::post('decode',[DataCrypter::class,'crypter']);
    Route::post('test',function (){ return "basarili"; });
    Route::post('create-post',[BlogController::class,'createPost']);
});


Route::post('login',[ApiController::class,'login']);

Route::post('main-info',[BlogController::class,'getMainInfo']);

Route::post('about-us',[BlogController::class,'getAboutUs']);

Route::post('send-contact',[BlogController::class,'sendContact']);
