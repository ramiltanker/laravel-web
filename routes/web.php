<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Общие руты для MainController
Route::get('/', [MainController::class, 'show_all_articles']);
Route::get('/one_article', [MainController::class, 'show_one_article']);
Route::get('/about_us', [MainController::class, 'show_about_us']);
Route::get('/contacts', [MainController::class, 'show_contacts']);

// Руты для работы с пользователями
Route::get('/register', [AuthController::class, 'registration']);
Route::post('/create_user', [AuthController::class, 'create_user']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logOut']);

// Руты для работы со статьями
Route::resource('/article', ArticleController::class);
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