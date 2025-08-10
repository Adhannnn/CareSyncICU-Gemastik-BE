<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;

class UserController extends Controller
{
   // Controller method (UserController.php)
public function getPatientByPhone(Request $request)
{
    $phone = $request->query('phone');
    $user = User::where('phone', $phone)->first();

    if (!$user || !$user->patient_id) {
        return response()->json([
            'message' => 'User atau pasien tidak ditemukan',
        ], 404);
    }

    $patient = Patient::find($user->patient_id);
    if (!$patient) {
        return response()->json([
            'message' => 'Data pasien tidak ditemukan',
        ], 404);
    }

    return response()->json($patient);
}
}
