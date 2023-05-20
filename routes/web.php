<?php


use Illuminate\Support\Facades\Route;



// use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\VerificationController;
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

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();
// Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::group(['middleware' => ['auth']], function () {

//     /**
//      * Verification Routes
//      */
//     Route::get('/email/verify', 'VerificationController@show')->name('verification.notice');
//     Route::get('/email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify')->middleware(['signed']);
//     Route::post('/email/resend', 'VerificationController@resend')->name('verification.resend');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
