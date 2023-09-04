<?php

use App\Http\Controllers\API\BacaanSholatController;
use App\Http\Controllers\Konten\AmalYaumiController;
use App\Http\Controllers\Konten\ArtikelDakwahController;
use App\Http\Controllers\Konten\InfoKajianController;
use App\Http\Controllers\Konten\PendataanHafalanController;
use App\Http\Controllers\Konten\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSystem\AuthController;
use App\Http\Controllers\LoginSystem\LoginController;
use App\Http\Controllers\LoginSystem\PasswordController;
use App\Http\Controllers\LoginSystem\AuthMobileController;
use App\Http\Controllers\LoginSystem\VerificationController;
use App\Http\Controllers\Profil\ProfilController;

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
    Route::post('resendVerificationOtp', [VerificationController::class, 'resendVerificationOtp']);
//mobile end
//web
    Route::post('register', [AuthController::class, 'register']);
    Route::post('registerAdmin', [AuthController::class, 'registerAdmin']);
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('resendVerification', [VerificationController::class, 'resendVerification']);

//web end
    Route::post('login', [LoginController::class, 'login']);
    Route::post('sendResetLink', [PasswordController::class, 'sendResetLink']);
    Route::post('resetPassword', [PasswordController::class, 'resetPassword']);
});

Route::group(['middleware' => ['auth:api', 'role:user,admin']], function(){
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('changePassword/{id}', [PasswordController::class, 'changePassword']);

    Route::get('searchUser', [SearchController::class, 'searchUser']);
    Route::get('searchKonten', [SearchController::class, 'searchKonten']);


    Route::post('createHafalan', [PendataanHafalanController::class, 'createHafalan']);
    Route::post('showHafalan', [PendataanHafalanController::class, 'showHafalan']);
    Route::post('updateHafalan/{id}', [PendataanHafalanController::class, 'updateHafalan']);
    Route::post('deleteHafalan/{id}', [PendataanHafalanController::class, 'deleteHafalan']);

    Route::post('showProfil', [ProfilController::class, 'showProfil']);
    Route::post('updateProfil/{id}', [ProfilController::class, 'updateProfil']);
    Route::post('deleteAcc/{id}', [ProfilController::class, 'deleteAcc']);

    Route::post('createAmal', [AmalYaumiController::class, 'createAmal']);
    Route::post('showAmal', [AmalYaumiController::class, 'showAmal']);
});

Route::group(['middleware' => ['auth:api', 'role:admin']], function(){
    Route::post('createKajian', [InfoKajianController::class, 'createKajian']);
    Route::post('updateKajian/{id}', [InfoKajianController::class, 'updateKajian']);
    Route::post('deleteKajian/{id}', [InfoKajianController::class, 'deleteKajian']);

    Route::post('createArtikel', [ArtikelDakwahController::class, 'createArtikel']);
    Route::post('updateArtikel/{id}', [ArtikelDakwahController::class, 'updateArtikel']);
    Route::post('deleteArtikel/{id}', [ArtikelDakwahController::class, 'deleteArtikel']);

    Route::post('createBacaan', [BacaanSholatController::class, 'createBacaan']);
    Route::post('showBacaan', [BacaanSholatController::class, 'showBacaan']);

    Route::post('showAllUser', [ProfilController::class, 'showAllUser']);
    Route::post('showOneUser/{id}', [ProfilController::class, 'showOneUser']);
});

    Route::post('showKajian', [InfoKajianController::class, 'showKajian']);
    Route::post('showOneKajian/{id}', [InfoKajianController::class, 'showOneKajian']);
    Route::post('showArtikel', [ArtikelDakwahController::class, 'showArtikel']);
    Route::post('showOneArtikel/{id}', [ArtikelDakwahController::class, 'showOneArtikel']);
