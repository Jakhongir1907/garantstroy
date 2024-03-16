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
use App\Http\Controllers\Api\HouseTradeController;
use App\Http\Controllers\Api\HouseTradeExpenseController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\HiredWorkerController;
use App\Http\Controllers\Api\HiredWorkerExpenseController;
use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\ContractFloorController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\DayOffController;
use App\Http\Controllers\Api\AdvancePaymentController;
use App\Http\Controllers\Api\WorkerAccountController;


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

Route::middleware(['auth:sanctum'])->group(function (){
// Workers CRUD
    Route::apiResource('workers' , WorkerController::class);
    Route::post('/filter/workers' , [WorkerController::class , 'filterData']);

    Route::apiResource('expenses' ,\App\Http\Controllers\Api\ExpenseController::class);
    Route::apiResource('expense-items' ,\App\Http\Controllers\Api\ExpenseItemController::class);

    Route::apiResource('users' , \App\Http\Controllers\Api\UserController::class);
// Projects CRUD
    Route::apiResource('projects' , ProjectController::class);

    Route::get('/all-projects' , [ProjectController::class , 'allData']);

    Route::middleware(['admin'])->group(function (){
//     Other Expenses
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
// House Trade CRUD

    Route::apiResource('house-trades' , HouseTradeController::class);
    // House Trade Expenses
    Route::apiResource('house-trade-expenses' , HouseTradeExpenseController::class);
    Route::get('/trade-expenses/{house_trade_id}' , [HouseTradeExpenseController::class , 'getByHouseTrade']);


    //    Route::post('/filter/house-trade-expenses' , [HouseTradeExpenseController::class , 'filterData']);
//

//
// Hired Workers CRUD
    Route::apiResource('hired-workers' , HiredWorkerController::class);
    Route::post('/filter/hired-workers' , [HiredWorkerController::class , 'filterData']);
//
// CONTRACTS CRUD
    Route::apiResource('contracts' , ContractController::class);
    Route::post('/filter/contracts' , [ContractController::class , 'filterData']);
//
// Contract Floors
    Route::apiResource('floors' , ContractFloorController::class);
    Route::get('/contract-floors/{contract_id}' , [ContractFloorController::class , 'filterData']);
//
// Hired Worker Expenses
    Route::apiResource('hired-worker-expenses' ,HiredWorkerExpenseController::class);
    Route::get('/hired-expenses/{hired_worker_id}' , [HiredWorkerExpenseController::class , 'getByWorker']);
//
// Tools CRUD
    Route::apiResource('tools' , ToolController::class);
    Route::post('/filter/tools' , [ToolController::class , 'filterData']);
// Incomes CRUD
    Route::apiResource('incomes' , IncomeController::class);
    Route::post('/filter/incomes' , [IncomeController::class , 'filterData']);

    Route::apiResource('day-offs' , DayOffController::class);
    Route::apiResource('advance-payments' , AdvancePaymentController::class);
    Route::apiResource('worker-accounts' , WorkerAccountController::class);


// Excel Exports


        Route::post('/filter/salary' , [WorkerController::class , 'salary']);
    Route::post('/store/dayoff' , [WorkerController::class , 'dayoff']);
    Route::post('/store/payment' , [WorkerController::class , 'payment']);
    Route::post('/start/work' , [WorkerController::class , 'start_work']);
    Route::post('/finish/work' , [WorkerController::class , 'finish_work']);

        // Image Upload and Delete Image
    Route::post('/image-upload', [ImageUploadController::class, 'imageUpload']);
    Route::post('/image-delete', [ImageUploadController::class, 'imageDelete']);
//

});
});
Route::get('/trade-expenses/export/{house_trade_id}' , [HouseTradeExpenseController::class , 'exportExcel']);
Route::get('/contracts/export/{contract_id}' , [ContractFloorController::class , 'exportExcel']);
Route::get('/hired-workers/export/{hired_worker_id}' , [HiredWorkerExpenseController::class , 'exportExcel']);
Route::get('/incomes/export/{project_id}' , [IncomeController::class , 'exportExcel']);







