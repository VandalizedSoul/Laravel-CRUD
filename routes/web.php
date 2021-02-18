<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TaskController;
require __DIR__.'/auth.php';
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

//Salesforce authentication for server
Route::get('/authenticate', function()
{
    return Forrest::authenticate();
});
//Callback after successful authentication
Route::get('/callback', function()
{
    Forrest::callback();
    return Redirect::to('/');
});

// Route::get('mylog',[TestController::class,'show'])->middleware(['myLog:okkk']);

Route::group(['middleware' => 'auth'], function() {
    Route::resource('task', TaskController::class);

    Route::get('/', function () {
        return view('dashboard');
    });
    Route::get('/create', function () {
        return view('dashboard');
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');  
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
