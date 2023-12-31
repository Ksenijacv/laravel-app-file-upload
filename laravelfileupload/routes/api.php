<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('posts', [PostController::class, 'index']); 



Route::post('posts', [PostController::class, 'store']); 

Route::put('posts/{id}', [PostController::class, 'update']);

Route::delete('posts/{id}', [PostController::class, 'destroy']);

//ruta za gledanje kesiranih postova
Route::get('/cached-posts', [PostController::class, 'showCachedPosts']);

//search
Route::get('/search/{name}', [PostController::class, 'search']);

//export u csv
Route::get('/export-csv', [PostController::class, 'exportToCSV']);

//export u excel
Route::get('/export-excel', [PostController::class, 'exportToExcel']);

//prikaz sa paginacijom
Route::get('posts-pagination', [PostController::class, 'indexWithPagination']); 

//login i registracija
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//testiranje rute za autentifikaciju
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('posts/{id}', [PostController::class, 'show']); 
    Route::post('logout', [AuthController::class, 'logout']);
});

