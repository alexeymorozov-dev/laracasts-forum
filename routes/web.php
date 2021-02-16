<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ThreadSubscriptionController;
use App\Http\Controllers\UserNotificationController;
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

/* Home */
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');

/* Auth */
Auth::routes();

/* Threads */
Route::get('/threads', [ThreadController::class, 'index']);
Route::get('/threads/create', [ThreadController::class, 'create']);
Route::get('threads/{channel}', [ThreadController::class, 'index']);
Route::post('/threads', [ThreadController::class, 'store']);
Route::get('/threads/{channel}/{thread}', [ThreadController::class, 'show']);
Route::delete('/threads/{channel}/{thread}', [ThreadController::class, 'destroy']);

/* Subscriptions */
Route::middleware('auth')->group(function () {
    Route::post('/threads/{channel}/{thread}/subscriptions',
        [ThreadSubscriptionController::class, 'store']);
    Route::delete('/threads/{channel}/{thread}/subscriptions',
        [ThreadSubscriptionController::class, 'destroy']);
});

/* Replies */
Route::get('/threads/{channel}/{thread}/replies', [ReplyController::class, 'index']);
Route::post('/threads/{channel}/{thread}/replies', [ReplyController::class, 'store']);
Route::patch('/replies/{reply}', [ReplyController::class, 'update']);
Route::delete('/replies/{reply}', [ReplyController::class, 'destroy']);

/* Favorites */
Route::post('/reply/{reply}/favorites', [FavoriteController::class, 'store']);
Route::delete('/reply/{reply}/favorites', [FavoriteController::class, 'destroy']);

/* Profile */
Route::get('profiles/{user}', [ProfileController::class, 'show'])->name('profile');
Route::get('profiles/{user}/notifications', [UserNotificationController::class, 'index']);
Route::delete('profiles/{user}/notifications/{notification}', [UserNotificationController::class, 'destroy']);
