<?php


use App\Http\Controllers\BkashTokenizePaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\PatientController;


Route::group([
'prefix'     => 'patient',
'middleware' => ['auth', 'verified','role:patient'],
], function () {
    Route::get('/', [PatientController::class, 'index'])
          ->name('patient');
    Route::get('/messages', [PatientController::class, 'messages'])
        ->name('patient.messages');
    Route::get('/appointments', [PatientController::class, 'appointments'])
        ->name('patient.appointments');
    Route::post('/appointments/create', [PatientController::class, 'createAppointment'])
        ->name('patient.appointments.create');
    Route::get('/consultation', [PatientController::class, 'consultations'])
        ->name('patient.consultations');
    Route::get('/medicines', [PatientController::class, 'medicines'])
        ->name('patient.medicines');
    Route::get('/health', [PatientController::class, 'healthRecords'])
        ->name('patient.health-records');
    Route::get('/payments', [PatientController::class, 'payments'])
        ->name('patient.payments');
    Route::get('/profile', [PatientController::class, 'profile'])
        ->name('patient.profile');
    Route::get('/blood-bank', [PatientController::class, 'bloodBank'])
        ->name('patient.blood-bank');
    Route::get('/settings', [PatientController::class, 'settings'])
        ->name('patient.settings');

    Route::get('/bkash/create-payment', [BkashTokenizePaymentController::class,'createPayment'])->name('bkash-create-payment');
    Route::get('/bkash/callback', [BkashTokenizePaymentController::class,'callBack'])->name('bkash-callBack');

});