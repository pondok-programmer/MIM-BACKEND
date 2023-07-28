<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSystem\AuthController;
use App\Http\Controllers\LoginSystem\LoginController;
use App\Http\Controllers\LoginSystem\PasswordController;
use App\Http\Controllers\LoginSystem\AuthMobileController;
use App\Http\Controllers\LoginSystem\VerificationController;

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

Route::group(['middleware' => ['guest']], function(){
    //mobile
    Route::post('registerMobile', [AuthMobileController::class, 'registerMobile']);
    Route::post('regiseterMobileAdmin', [AuthMobileController::class, 'regiseterMobileAdmin']);
    Route::get('verify-email-mobile', [VerificationController::class, 'verifyOtp']);
//mobile end
//web
    Route::post('register', [AuthController::class, 'register']);
    Route::post('registerAdmin', [AuthController::class, 'registerAdmin']);
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
//web end
    Route::post('login', [LoginController::class, 'login']);
    Route::post('sendResetLink', [PasswordController::class, 'sendResetLink']);
    Route::post('resetPassword', [PasswordController::class, 'resetPassword']);
});

Route::group(['middleware' => ['auth:api', 'role:user,admin']], function(){
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('changePassword/{id}', [PasswordController::class, 'changePassword']);
    
 });
