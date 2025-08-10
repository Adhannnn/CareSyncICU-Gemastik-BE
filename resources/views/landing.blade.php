@extends('base')

@section('content')
<div class="card shadow-lg p-4">
    <h3 class="mb-4">Daftar Pasien yang Diupdate</h3>
    <div class="list-group">
        @forelse ($patients as $patient)
        <a class="list-group-item list-group-item-action hover-effect" href="{{ route('access_patient', $patient->id) }}">
            {{ $patient->name }}
        </a>
        @empty
        <p class="text-muted">Belum ada pasien yang diupdate.</p>
        @endforelse
    </div>
</div>
@endsection
