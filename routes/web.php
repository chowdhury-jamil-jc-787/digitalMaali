<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\issueController;
use App\Http\Controllers\solvedController;
use App\Http\Controllers\finalSolved;
use App\Http\Controllers\UserController;
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

Route::get('/issues', function () {
    return view('issues');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    
    return redirect('admin');
})->name('dashboard');

Route::get('/logout', [logoutController::class,'index'])->name('logout');

Route::get('issue',[issueController::class,'show'])->name('issuelist');

Route::get('solved/{id}',[solvedController::class,'solve']);


Route::get('solution/{data}',[finalSolved::class,'solution']);
//Route::post('solution/{data}',[finalSolved::class,'solution']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get ('/admin',[UserController::class, 'show']);

Route::get('/chart', [App\Http\Controllers\ChartController::class, 'show'])->name('chart');
Route::get('/userlist', [App\Http\Controllers\userlistController::class, 'show'])->name('userlist');
Route::get('/profile/{id}', [App\Http\Controllers\profileController::class, 'show'])->name('profile');


Route::get('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');

