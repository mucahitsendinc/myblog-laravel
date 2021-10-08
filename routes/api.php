<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogAdminController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DataCrypter;

Route::middleware('login')->group(function (){
    /**
     * Blog Test Routes
     */
    Route::post('encode',[DataCrypter::class,'crypter']);
    Route::post('decode',[DataCrypter::class,'crypter']);
    Route::post('test',function (){ return "basarili"; });

    /*Route::get('migrate',function(){
        Artisan::call('migrate:refresh');
        dd('migrated!');
    });
    Route::get('reboot',function(){
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('key:generate');
        dd('cache temizlendi!');
    });*/

    /**
     * Blog Admin ROUTES
    */
    Route::post('posts-without-recommendeds',[BlogAdminController::class,'getPostsWithoutRecommended']);

    Route::post('create-post',[BlogAdminController::class,'createPost']);
    Route::post('create-recommended',[BlogAdminController::class,'createRecommended']);

    Route::post('new-image',[ImageController::class,'newImage']);
    Route::post('delete-image',[ImageController::class,'deleteImage']);
    Route::post('images',[ImageController::class,'getImages'])->middleware('cacheimages');

    Route::post('update-main-info',[BlogAdminController::class,'updateMainInfo']);

});



Route::post('test-time',function(){
    return date('Y-m-d H:i:s');
});

Route::post('login',[ApiController::class,'login']);

Route::post('main-info',[BlogController::class,'getMainInfo']);

Route::post('posts',[BlogController::class,'getPosts'])->middleware('cacheposts');

Route::post('post-detail',[BlogController::class,'getPostDetail']);

Route::post('comments',[BlogController::class,'getComments']);

Route::post('about-us',[BlogController::class,'getAboutUs']);

Route::post('recommended',[BlogController::class,'getRecommended']);

Route::post('send-contact',[BlogController::class,'sendContact']);

Route::post('send-comment',[BlogController::class,'sendComment']);

