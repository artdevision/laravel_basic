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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', static function (Request $request) {
        return $request->user();
    });

    // Users Controller
    Route::prefix('users')->namespace('App\\Http\\Controllers\\Api')->group(static function () {
        Route::get('/', [
            'uses' => 'UsersController@list',
        ]);
    });

    // Posts Controller
    Route::prefix('posts')->namespace('App\\Http\\Controllers\\Api')->group(static function () {
        Route::get('/', [
            'uses' => 'PostsController@list',
        ]);

        Route::get('/{id}', [
            'uses' => 'PostsController@index',
        ]);

        Route::post('/create', [
            'uses' => 'PostsController@create',
        ]);

        Route::post('/{id}', [
            'uses' => 'PostsController@update',
        ]);

        Route::delete('/{id}', [
            'uses' => 'PostsController@delete',
        ]);

        // Post comments
        Route::get('/{post_id}/comments', [
            'uses' => 'CommentsController@list',
        ]);

        Route::post('/{post_id}/comments', [
            'uses' => 'CommentsController@create',
        ]);

        Route::post('/{post_id}/comments/{id}', [
            'uses' => 'CommentsController@update',
        ]);

        Route::delete('/{post_id}/comments/{id}', [
            'uses' => 'CommentsController@delete',
        ]);
    });
});
