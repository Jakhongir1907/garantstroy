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
use App\Http\Controllers\Api\OtherExpenseController;
use App\Http\Controllers\Api\CarExpenseController;
use App\Http\Controllers\Api\HouseholdExpenseController;
use App\Http\Controllers\ImageUploadController;



use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordChangeController;


Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::post('/change-password', [PasswordChangeController::class, 'changePassword'])
    ->middleware('auth:sanctum')
    ->name('change.password');

Route::get('/get-user' , function (){
    $user = \Illuminate\Support\Facades\Auth::user();
    $role = "user";
    if($user->is_admin ==1){
        $role = "admin";
        return response()->json([
            'user' => $user ,
            'role_user' => $role
        ]);
    }
    return response()->json([
        'user' => $user ,
        'role_user' => $role
    ]);
})->middleware('auth:sanctum');

//Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//    ->middleware('guest')
//    ->name('password.email');
//
//Route::post('/reset-password', [NewPasswordController::class, 'store'])
//    ->middleware('guest')
//    ->name('password.store');
//
//Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
//    ->middleware(['auth', 'signed', 'throttle:6,1'])
//    ->name('verification.verify');
//
//Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//    ->middleware(['auth', 'throttle:6,1'])
//    ->name('verification.send');

//Route::middleware(['auth:sanctum'])->group(function (){
//
//    Route::middleware(['admin'])->group(function (){
    // Other Expenses
    Route::apiResource('other-expenses' , OtherExpenseController::class);
    Route::get('/latest/other-expenses/{days}' , [OtherExpenseController::class , 'lastDays']);
    Route::post('/filter/other-expenses' , [OtherExpenseController::class , 'filterData']);
//
// Car Expenses
    Route::apiResource('car-expenses' , CarExpenseController::class);
    Route::get('/latest/car-expenses/{days}' , [CarExpenseController::class , 'lastDays']);
    Route::post('/filter/car-expenses' , [CarExpenseController::class , 'filterData']);
// Household Expenses
    Route::apiResource('household-expenses' , HouseholdExpenseController::class);
    Route::get('/latest/household-expenses/{days}' , [HouseholdExpenseController::class , 'lastDays']);
    Route::post('/filter/household-expenses' , [HouseholdExpenseController::class , 'filterData']);
//
// Image Upload and Delete Image
    Route::post('/image-upload', [ImageUploadController::class, 'imageUpload']);
    Route::post('/image-delete', [ImageUploadController::class, 'imageDelete']);
//

//});
//});










