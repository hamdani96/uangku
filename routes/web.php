<?php

use App\Http\Controllers\ExpenseController;
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

Route::get('/', function () {
    return view('pin');
})->name('pin');

Route::prefix('expense')->group(function() {
    Route::get('/{pin}', [ExpenseController::class, 'index'])->name('expense.index');
    Route::get('/list/{pin}', [ExpenseController::class, 'list'])->name('expense.list');
    Route::post('/store', [ExpenseController::class, 'store'])->name('expense.store');
    Route::delete('/delete/{id}', [ExpenseController::class, 'delete'])->name('expense.delete');
});

Route::prefix('insight')->group(function() {
    Route::get('/{pin}', [ExpenseController::class, 'insight'])->name('insight.index');
    Route::get('/circle-chart/{id}', [ExpenseController::class, 'circleChart'])->name('insight.circleChart');
    Route::get('/bar-chart/{id}', [ExpenseController::class, 'barChart'])->name('insight.barChart');
});
