@extends('base')

@section('content')
<div class="card shadow-lg p-4">
    <h3 class="mb-4">Detail Pasien: {{ $patient->name }}</h3>
    @if (session('nurse'))
    <form method="POST" class="mb-4" action="{{ route('patient_detail', $patient->id) }}">
        @csrf
        <div class="input-group">
            <input type="text" name="condition" class="form-control" placeholder="Masukkan kondisi terbaru" required>
            <button type="submit" class="btn btn-success">Update Kondisi</button>
        </div>
    </form>
    @endif
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Informasi Pasien</h5>
            @if ($patient->face_image)
                <div class="mb-3">
                    <label class="form-label">Foto Wajah Pasien</label>
                    <img src="{{ $patient->face_image }}" class="img-fluid" style="max-height: 200px;" alt="Foto Wajah Pasien">
                </div>
            @endif
            <p><strong>Nama Lengkap:</strong> {{ $patient->name }}</p>
            <p><strong>Key Akses:</strong> {{ $patient->access_key }}</p>
            <p><strong>Kondisi Terakhir:</strong> {{ $patient->condition ?: '-' }}</p>
            <p><strong>Nama Anggota Keluarga:</strong> {{ $patient->family_member_name ?: '-' }}</p>
            <p><strong>Nomor Telepon:</strong> {{ $patient->phone_number ?: '-' }}</p>
            <p><strong>Nomor Telepon Darurat:</strong> {{ $patient->emergency_phone_number ?: '-' }}</p>
            <p><strong>No KTP:</strong> {{ $patient->id_card_number ?: '-' }}</p>
            <p><strong>Alamat:</strong> {{ $patient->address ?: '-' }}</p>
            @if ($patient->responsibleNurse)
                <p><strong>Penanggung Jawab Perawat:</strong> {{ $patient->responsibleNurse->username }}</p>
                @if ($patient->responsibleNurse->face_image)
                    <div class="mb-3">
                        <label class="form-label">Foto Wajah Perawat</label>
                        <img src="{{ $patient->responsibleNurse->face_image }}" class="img-fluid" style="max-height: 200px;" alt="Foto Wajah Perawat">
                    </div>
                @endif
            @else
                <p><strong>Penanggung Jawab Perawat:</strong> -</p>
            @endif
            <p><strong>Nomor Telepon Penanggung Jawab:</strong> {{ $patient->room_responsible_nurse_phone ?: '-' }}</p>
            <p><strong>Nama Dokter:</strong> {{ $patient->doctor_name ?: '-' }}</p>
            <p><strong>Nomor Telepon Dokter:</strong> {{ $patient->doctor_phone ?: '-' }}</p>
        </div>
    </div>
    <h5>Riwayat Kondisi</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Kondisi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($history as $entry)
                <tr>
                    <td>{{ $entry->condition }}</td>
                    <td>{{ $entry->timestamp }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">Belum ada riwayat kondisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection