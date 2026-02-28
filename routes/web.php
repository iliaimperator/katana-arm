<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Маршруты для управления услугами
Route::resource('services', ServiceController::class)->middleware(['auth']);

// Маршруты для управления запчастями
Route::resource('spare-parts', SparePartController::class)->middleware(['auth']);

// Клиенты и автомобили
Route::resource('clients', ClientController::class)->middleware(['auth']);
Route::resource('cars', CarController::class)->middleware(['auth']);

// Заказ-наряды
Route::resource('work-orders', WorkOrderController::class)->middleware(['auth']);
Route::post('work-orders/{workOrder}/add-service', [WorkOrderController::class, 'addService'])
    ->name('work-orders.add-service')->middleware(['auth']);

Route::delete('work-orders/{workOrder}/services/{service}', [WorkOrderController::class, 'removeService'])
    ->name('work-orders.remove-service')->middleware(['auth']);

Route::post('work-orders/{workOrder}/update-status', [WorkOrderController::class, 'updateStatus'])
    ->name('work-orders.update-status')->middleware(['auth']);

Route::post('work-orders/{workOrder}/add-part', [WorkOrderController::class, 'addPart'])
    ->name('work-orders.add-part')->middleware(['auth']);

Route::delete('work-orders/{workOrder}/parts/{part}', [WorkOrderController::class, 'removePart'])
    ->name('work-orders.remove-part')->middleware(['auth']);

Route::post('work-orders/{workOrder}/parts/{part}/update-status', [WorkOrderController::class, 'updatePartStatus'])
    ->name('work-orders.update-part-status')->middleware(['auth']);

// Дополнительные маршруты для управления услугами и запчастями в заказе
Route::post('work-orders/{workOrder}/add-service', [WorkOrderController::class, 'addService'])
    ->name('work-orders.add-service')->middleware(['auth']);

Route::post('work-orders/{workOrder}/add-part', [WorkOrderController::class, 'addPart'])
    ->name('work-orders.add-part')->middleware(['auth']);

// Маршруты для платежей
Route::post('work-orders/{workOrder}/add-payment', [WorkOrderController::class, 'addPayment'])
    ->name('work-orders.add-payment')->middleware(['auth']);

Route::delete('work-orders/{workOrder}/payments/{payment}', [WorkOrderController::class, 'removePayment'])
    ->name('work-orders.remove-payment')->middleware(['auth']);

Route::get('work-orders/{workOrder}/payment-info', [WorkOrderController::class, 'getPaymentInfo'])
    ->name('work-orders.payment-info')->middleware(['auth']);

// Отчеты и статистика
Route::prefix('reports')->name('reports.')->middleware(['auth'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/general', [ReportController::class, 'general'])->name('general');
    Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
    Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
    Route::get('/services', [ReportController::class, 'services'])->name('services');
});

// Маршрут для печати заказ-наряда
Route::get('work-orders/{workOrder}/print', [WorkOrderController::class, 'print'])
    ->name('work-orders.print')->middleware(['auth']);

// Маршрут для печати акта
Route::get('work-orders/{workOrder}/act', [WorkOrderController::class, 'act'])
    ->name('work-orders.act')->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
