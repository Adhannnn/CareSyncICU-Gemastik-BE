<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Cek nomor telepon penunggu (user)
    public function checkPhone(Request $request)
    {
        $phone = $request->query('phone');
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            // Nomor belum terdaftar
            return response()->json([
                'role' => null,
                'message' => 'Nomor tidak terdaftar',
            ]);
        }

        // Return info user, termasuk role dan flag firstLogin
        return response()->json([
            'role' => $user->role,
            'firstLogin' => $user->first_login,
            'user' => $user,
        ]);
    }

    // 2. Validasi key access pasien untuk user baru (first login)
    public function validateKeyAccess(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'key' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json(['valid' => false, 'message' => 'User tidak ditemukan']);
        }

        $patient = Patient::where('key_access', $request->key)->first();
        if (!$patient) {
            return response()->json(['valid' => false, 'message' => 'Key access tidak valid']);
        }

        // Pastikan pasien ini yang terkait dengan user belum punya patient_id, atau bisa override
        // Jika sudah terkait dengan pasien lain, bisa tolak

        // Update relasi user ke patient, dan set first_login = false
        $user->patient_id = $patient->id;
        $user->first_login = false;
        $user->save();

        return response()->json(['valid' => true]);
    }

    // 3. Validasi OTP dummy
    public function validateOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
            'isFirstLogin' => 'required|boolean',
        ]);

        // Dummy OTP validasi: OTP harus "123456" misal
        if ($request->otp === '123456') {
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false]);
    }

    public function resendOtp(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Nomor tidak terdaftar']);
    }

    // Dummy OTP baru (misalnya random 6 digit)
    $newOtp = rand(100000, 999999);

    // Kalau mau simpan di database (misalnya di kolom otp), tambahkan:
    // $user->otp = $newOtp;
    // $user->save();

    // Di sistem asli, ini biasanya kirim via SMS gateway
    return response()->json([
        'success' => true,
        'message' => 'Kode OTP baru berhasil dikirim',
        'otp' => $newOtp // sementara ditampilkan supaya mudah testing
    ]);
}


    // 4. Login admin dengan kode unik
    public function adminLogin(Request $request)
    {
        $request->validate([
            'unique_code' => 'required|string',
        ]);

        // Misal kode unik admin disimpan di db user dengan role = admin dan kolom 'unique_code'
        $user = User::where('role', 'admin')->where('unique_code', $request->unique_code)->first();

        if (!$user) {
            return response()->json(['valid' => false, 'message' => 'Kode unik admin tidak valid']);
        }

        return response()->json([
            'valid' => true,
            'user' => $user,
            'role' => 'admin',
        ]);
    }
}
