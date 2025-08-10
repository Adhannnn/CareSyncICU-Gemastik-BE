<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;

// Handle preflight OPTIONS requests untuk CORS
Route::options('/{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept')
        ->header('Access-Control-Max-Age', '3600');
})->where('any', '.*');

// OTP validation dengan CORS headers
Route::post('/validate-otp', function (Request $request) {
    $otp = $request->input('otp');

    if ($otp === '123456') {
        $response = response()->json([
            'success' => true,
            'message' => 'OTP valid'
        ]);
    } else {
        $response = response()->json([
            'success' => false,
            'message' => 'OTP salah'
        ], 400);
    }

    return $response
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept');
});

// Patient API routes
Route::get('/patients', [PatientController::class, 'apiGetPatients']);
Route::post('/patients', [PatientController::class, 'apiAddPatient']);
Route::get('/patients/{id}', [PatientController::class, 'apiGetPatientById']);
Route::put('/patients/{id}', [PatientController::class, 'apiUpdatePatient']);
Route::delete('/patients/{id}', [PatientController::class, 'apiDeletePatient']);

// Explicit OPTIONS handling untuk patient routes
Route::options('/patients', [PatientController::class, 'handleOptions']);
Route::options('/patients/{id}', [PatientController::class, 'handleOptions']);

?>