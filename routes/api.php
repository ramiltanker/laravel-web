<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ArticleController;

// Руты для работы с пользователями
Route::get('/register', [AuthController::class, 'registration']);
Route::post('/create_user', [AuthController::class, 'create_user']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logOut'])->middleware('auth:sanctum');

// Руты для работы со статьями
Route::resource('/article', ArticleController::class)->middleware('auth:sanctum');
Route::get('article/{article}', [ArticleController::class, 'show'])->name('article.show')->middleware('path-counter');

// Руты для работы с комментариями к статьям
Route::group(['prefix' => '/comment', 'middleware' => 'auth:sanctum'], function() {
	Route::get('/', [CommentController::class, 'index'])->name('comments');
	Route::post('/store', [CommentController::class, 'store']);
	Route::get('/edit/{id}', [CommentController::class, 'edit']);
	Route::post('/update/{id}', [CommentController::class, 'update']);
	Route::get('/delete/{id}', [CommentController::class, 'delete']);
	Route::get('/accept/{id}', [CommentController::class, 'accept']);
	Route::get('/reject/{id}', [CommentController::class, 'reject']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});