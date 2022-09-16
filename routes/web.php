<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/formHandler', [FormController::class, 'index'])->name('form');

/**
 * Billing and Subscriptions
 */
Route::get('/billing', function(){
    return view('pages.billing');
})->name('pricing');
Route::post('/billing-checkout', [BillingController::class, 'billingCheckout'])->name('billing-checkout');
Route::get('/thankyou', function(){
    return view('pages.thankyou');
})->name('thankyou');

Route::get('/webhook-event', [BillingController::class, 'webhookEvent'])->name('webhook-event');
