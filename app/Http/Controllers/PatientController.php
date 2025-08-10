<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ConditionHistory;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    // Helper method untuk menambahkan CORS headers
    private function addCorsHeaders($response)
    {
        return $response
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept')
            ->header('Access-Control-Max-Age', '3600');
    }

    public function landing()
    {
        $patients = Patient::whereNotNull('kondisi')->orderByDesc('id')->get(['id', 'name']);
        return view('landing', compact('patients'));
    }

    public function nurseDashboard(Request $request)
    {
        $searchQuery = $request->query('search', '');
        $query = Patient::query();
        if ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%');
        }
        $patients = $query->orderByDesc('id')->get();
        return view('nurse_dashboard', compact('patients', 'searchQuery'));
    }

    public function addPatient(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string',
            'ruangan' => 'nullable|string',
            'dokter_penjaga' => 'nullable|string',
            'jadwal_jenguk' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'foto_pasien' => 'nullable|image|max:1024', // max 1MB
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_pasien')) {
            $fotoPath = $request->file('foto_pasien')->store('foto_pasien', 'public'); // simpan di storage/app/public/foto_pasien
        }

        $patient = Patient::create([
            'name' => $request->name,
            'key_access' => Str::random(8),
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'ruangan' => $request->ruangan,
            'dokter_penjaga' => $request->dokter_penjaga,
            'jadwal_jenguk' => $request->jadwal_jenguk,
            'kondisi' => $request->kondisi,
            'foto_pasien' => $fotoPath,
        ]);

        return response()->json(['success' => true, 'patient' => $patient], 201);
    }


    public function patientDetail(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $history = ConditionHistory::where('patient_id', $id)->orderByDesc('timestamp')->get(['condition', 'timestamp']);

        if (!Session::has('nurse')) {
            $key = $request->query('key', '');
            if ($key !== $patient->key_access) {
                return redirect()->route('access_patient', $id);
            }
        }

        return view('patient_detail', compact('patient', 'history'));
    }

    public function updateCondition(Request $request, $id)
    {
        $request->validate([
            'kondisi' => 'required|string',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update(['kondisi' => $request->kondisi]);
        ConditionHistory::create([
            'patient_id' => $id,
            'condition' => $request->kondisi,
            'timestamp' => now(),
        ]);

        Session::flash('success', 'Kondisi pasien diperbarui!');
        return redirect()->route('patient_detail', $id);
    }

    public function accessPatient($id)
    {
        $patient = Patient::findOrFail($id);
        return view('access_patient', compact('patient'));
    }

    public function verifyAccess(Request $request, $id)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $patient = Patient::findOrFail($id);
        if ($request->key === $patient->key_access) {
            return redirect()->route('patient_detail', ['id' => $id, 'key' => $request->key]);
        }

        Session::flash('danger', 'Key akses salah! Hubungi perawat.');
        return redirect()->route('access_patient', $id);
    }

    // ========== API METHODS WITH CORS ==========

    public function apiGetPatients()
    {
        try {
            $patients = Patient::orderByDesc('id')->get();
            $response = response()->json($patients);
            return $this->addCorsHeaders($response);
        } catch (\Exception $e) {
            $response = response()->json([
                'error' => 'Failed to fetch patients',
                'message' => $e->getMessage()
            ], 500);
            return $this->addCorsHeaders($response);
        }
    }

    public function apiGetPatientById($id)
    {
        try {
            $patient = Patient::find($id);
            if (!$patient) {
                $response = response()->json(['message' => 'Data tidak ditemukan'], 404);
                return $this->addCorsHeaders($response);
            }
            $response = response()->json($patient);
            return $this->addCorsHeaders($response);
        } catch (\Exception $e) {
            $response = response()->json([
                'error' => 'Failed to fetch patient',
                'message' => $e->getMessage()
            ], 500);
            return $this->addCorsHeaders($response);
        }
    }

    public function apiAddPatient(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string',
            'ruangan' => 'nullable|string',
            'dokter_penjaga' => 'nullable|string',
            'jadwal_jenguk' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'foto_pasien' => 'nullable|image|max:1024', 
        ]);

        // Handle file upload like in your addPatient method
        $fotoPath = null;
        if ($request->hasFile('foto_pasien')) {
            $fotoPath = $request->file('foto_pasien')->store('foto_pasien', 'public');
        }

        $patient = Patient::create([
            'name' => $validated['name'],
            'key_access' => Str::random(8), 
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'ruangan' => $validated['ruangan'],
            'dokter_penjaga' => $validated['dokter_penjaga'],
            'jadwal_jenguk' => $validated['jadwal_jenguk'],
            'kondisi' => $validated['kondisi'],
            'foto_pasien' => $fotoPath, 
        ]);

        $response = response()->json($patient, 201);
        return $this->addCorsHeaders($response);
    } catch (\Illuminate\Validation\ValidationException $e) {
        $response = response()->json([
            'error' => 'Validation failed',
            'message' => $e->errors()
        ], 422);
        return $this->addCorsHeaders($response);
    } catch (\Exception $e) {
        $response = response()->json([
            'error' => 'Failed to create patient',
            'message' => $e->getMessage()
        ], 500);
        return $this->addCorsHeaders($response);
    }
}

    public function apiUpdatePatient(Request $request, $id)
{
    try {
        $patient = Patient::find($id);
        if (!$patient) {
            $response = response()->json(['message' => 'Data tidak ditemukan'], 404);
            return $this->addCorsHeaders($response);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string',
            'ruangan' => 'nullable|string',
            'dokter_penjaga' => 'nullable|string',
            'jadwal_jenguk' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'foto_pasien' => 'nullable|image|max:1024',
        ]);

        // Update data text
        $patient->update($validated);

        // Handle file upload jika ada
        if ($request->hasFile('foto_pasien')) {
            $file = $request->file('foto_pasien');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/foto_pasien', $filename);

            $patient->foto_pasien = 'foto_pasien/'.$filename;
            $patient->save();
        }

        $response = response()->json($patient);
        return $this->addCorsHeaders($response);
    } catch (\Illuminate\Validation\ValidationException $e) {
        $response = response()->json([
            'error' => 'Validation failed',
            'message' => $e->errors()
        ], 422);
        return $this->addCorsHeaders($response);
    } catch (\Exception $e) {
        $response = response()->json([
            'error' => 'Failed to update patient',
            'message' => $e->getMessage()
        ], 500);
        return $this->addCorsHeaders($response);
    }
}


    public function apiDeletePatient($id)
    {
        try {
            $patient = Patient::find($id);
            if (!$patient) {
                $response = response()->json(['message' => 'Data tidak ditemukan'], 404);
                return $this->addCorsHeaders($response);
            }

            $patient->delete();
            $response = response()->json(['message' => 'Data berhasil dihapus']);
            return $this->addCorsHeaders($response);
        } catch (\Exception $e) {
            $response = response()->json([
                'error' => 'Failed to delete patient',
                'message' => $e->getMessage()
            ], 500);
            return $this->addCorsHeaders($response);
        }
    }

    // Handle OPTIONS request untuk preflight CORS
    public function handleOptions()
    {
        $response = response('', 200);
        return $this->addCorsHeaders($response);
    }
}