<?php

use App\Http\Controllers\Api\ExtensionController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\UserAuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/register', [UserAuthController::class, 'register']);
Route::get('/ccdetails/{user_id?}', [ExtensionController::class, 'getCCDetails']);
Route::post('/setcard', [ExtensionController::class, 'setCustomCard']);

Route::post('/getproperty-details', [PropertyController::class, 'getPropertyDetails'])->name('getproperty-details');
Route::get('/getsubscription-details/{id}', [PropertyController::class, 'getSubscriptionDetails'])->name('getsubscription-details');
Route::get('/getproperty-history/{id}', [PropertyController::class, 'getPropertyHistory'])->name('getproperty-history');
Route::post('/cancel-subscription', [PropertyController::class, 'cancelSubscription'])->name('cancel-subscription');