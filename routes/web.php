<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventDetailController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketManualController;

Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/evento/criacao', [EventController::class, 'create'])->middleware('auth');
Route::post('/eventos', [EventController::class, 'store']);
Route::get('/evento/editar/{id}', [EventController::class, 'edit'])->middleware('auth');
Route::put('/evento/modificar/{id}', [EventController::class, 'update'])->middleware('auth');
Route::delete('/evento/{id}', [EventController::class, 'destroy'])->middleware('auth');

Route::get('/evento/{id}', [EventDetailController::class, 'show'])->name('event.show');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/eventos-usuario', [DashboardController::class, 'userEvents'])->name('dashboard.user-events')->middleware('auth');
Route::get('/dashboard/eventos-criados', [DashboardController::class, 'createdEvents'])->name('dashboard.created-events')->middleware('auth');

Route::post('evento/presenca/{id}', [RegistrationController::class, 'joinEventConfirm'])->name('event.join')->middleware('auth');
Route::delete('evento/cancelar/{id}', [RegistrationController::class, 'cancelRegistration'])->middleware('auth');
Route::get('evento/estorno/{id}', [RegistrationController::class, 'showRefundRequestForm'])->name('refund.request')->middleware('auth');
Route::post('evento/estorno/{id}', [RegistrationController::class, 'submitRefundRequest'])->name('refund.submit')->middleware('auth');

Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('ticket.show')->middleware('auth');

Route::get('/payment/{eventId}', [PaymentController::class, 'show'])->name('payment.show')->middleware('auth');
Route::post('/payment/{eventId}', [PaymentController::class, 'process'])->name('payment.process')->middleware('auth');

Route::get('/ticket/manual/{eventId}', [TicketManualController::class, 'showManualForm'])->name('ticket.manual.form')->middleware('auth');
Route::post('/ticket/manual/{eventId}', [TicketManualController::class, 'createManualTicket'])->name('ticket.manual')->middleware('auth');

Route::get('/healthz', function () {
    return response()->json(['status' => 'ok'], 200);
});
