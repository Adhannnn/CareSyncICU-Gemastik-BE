<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Middleware\NurseMiddleware;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PatientController::class, 'landing'])->name('landing');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(NurseMiddleware::class)->group(function () {
    Route::get('/nurse', [PatientController::class, 'nurseDashboard'])->name('nurse_dashboard');
    Route::post('/nurse', [PatientController::class, 'addPatient']);
});

Route::get('/patient', [PatientController::class, 'PatientController@apiGetPatients']);
Route::get('/patient/{id}', [PatientController::class, 'patientDetail'])->name('patient_detail');
Route::post('/patient/{id}', [PatientController::class, 'updateCondition'])->middleware(NurseMiddleware::class);
Route::get('/access/{id}', [PatientController::class, 'accessPatient'])->name('access_patient');
Route::post('/access/{id}', [PatientController::class, 'verifyAccess']);

/*
|--------------------------------------------------------------------------
| API Routes untuk React Frontend
|--------------------------------------------------------------------------
| Semua route di bawah ini akan mengembalikan data dalam bentuk JSON
| dan bisa diakses dari frontend React + Tailwind.
*/



Route::get('/api/check-phone', [AuthController::class, 'checkPhone']);
Route::post('/api/validate-keyaccess', [AuthController::class, 'validateKeyAccess']);
Route::post('/validate-otp', function (Illuminate\Http\Request $request) {
    $otp = $request->input('otp');

    if ($otp === '123456') {
        return response()->json([
            'success' => true,
            'message' => 'OTP valid'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'OTP salah'
    ], 400);
});
Route::post('/api/admin-login', [AuthController::class, 'adminLogin']);
// Route
Route::get('/api/patient-by-phone', [UserController::class, 'getPatientByPhone']);

