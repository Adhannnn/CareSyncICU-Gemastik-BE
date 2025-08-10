@extends('base')

@section('content')
<div class="card shadow-lg p-4">
    <h3 class="mb-4">Dashboard Perawat</h3>
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari pasien..." value="{{ $searchQuery }}">
            <button type="submit" class="btn btn-outline-primary">Cari</button>
        </div>
    </form>
    <button type="button" class="btn btn-success mb-4" id="toggleFormBtn">Tambah Pasien</button>

    <!-- Collapsible Form Section -->
    <div class="collapsible-form" id="addPatientFormSection" style="max-height: 0; overflow: hidden;">
        <div class="form-wrapper">
            <form method="POST" id="addPatientForm" action="{{ route('nurse_dashboard') }}" onsubmit="return preparePatientFaceImage()">
                @csrf
                <div class="mb-3">
                    <label for="patient_name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="patient_name" id="patient_name" class="form-control shake-on-focus" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label for="face_image" class="form-label">Foto Wajah Pasien</label>
                    <div class="d-flex flex-column align-items-center">
                        <video id="patientVideo" width="100%" height="auto" autoplay style="display: none;"></video>
                        <canvas id="patientCanvas" style="display: none;"></canvas>
                        <img id="patientPreview" class="img-fluid mb-2" style="max-height: 200px; display: none;">
                        <input type="hidden" name="face_image" id="patient_face_image">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" id="patientCaptureBtn">Ambil Foto</button>
                            <input type="file" id="patientUploadImage" accept="image/*" class="form-control" style="max-width: 200px;">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="family_member_name" class="form-label">Nama Anggota Keluarga</label>
                    <input type="text" name="family_member_name" id="family_member_name" class="form-control shake-on-focus" placeholder="Masukkan nama anggota keluarga">
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control shake-on-focus" placeholder="Masukkan nomor telepon">
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control shake-on-focus" onchange="calculateAge()">
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Usia</label>
                    <input type="text" name="age" id="age" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label for="emergency_phone_number" class="form-label">Nomor Telepon Darurat</label>
                    <input type="text" name="emergency_phone_number" id="emergency_phone_number" class="form-control shake-on-focus" placeholder="Masukkan nomor telepon darurat">
                </div>
                <div class="mb-3">
                    <label for="id_card_number" class="form-label">No KTP</label>
                    <input type="text" name="id_card_number" id="id_card_number" class="form-control shake-on-focus" placeholder="Masukkan nomor KTP">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea name="address" id="address" class="form-control shake-on-focus" placeholder="Masukkan alamat"></textarea>
                </div>
                <div class="mb-3">
                    <label for="room_responsible_nurse_id" class="form-label">Pilih Penanggung Jawab Perawat</label>
                    <select name="room_responsible_nurse_id" id="room_responsible_nurse_id" class="form-control shake-on-focus">
                        <option value="">-- Pilih Perawat --</option>
                        @foreach ($nurses as $nurse)
                            <option value="{{ $nurse->id }}">{{ $nurse->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="room_responsible_nurse_phone" class="form-label">Nomor Telepon Penanggung Jawab</label>
                    <input type="text" name="room_responsible_nurse_phone" id="room_responsible_nurse_phone" class="form-control shake-on-focus" placeholder="Masukkan nomor telepon penanggung jawab">
                </div>
                <div class="mb-3">
                    <label for="doctor_name" class="form-label">Nama Dokter</label>
                    <input type="text" name="doctor_name" id="doctor_name" class="form-control shake-on-focus" placeholder="Masukkan nama dokter">
                </div>
                <div class="mb-3">
                    <label for="doctor_phone" class="form-label">Nomor Telepon Dokter</label>
                    <input type="text" name="doctor_phone" id="doctor_phone" class="form-control shake-on-focus" placeholder="Masukkan nomor telepon dokter">
                </div>
                <button type="submit" class="btn btn-success w-100">Simpan Pasien</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Pasien</th>
                    <th>Key Akses</th>
                    <th>Kondisi Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patients as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->access_key }}</td>
                    <td>{{ $p->condition ?: '-' }}</td>
                    <td>
                        <a href="{{ route('patient_detail', $p->id) }}" class="btn btn-primary btn-sm">Update</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada pasien.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function calculateAge() {
        let birthDate = document.getElementById('birth_date').value;
        if (birthDate) {
            let today = new Date();
            let birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            let monthDiff = today.getMonth() - birth.getMonth();

            // Jika belum lewat bulan/tanggal ulang tahun, kurangi umur 1
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }

            document.getElementById('age').value = age;
        }
}

function preparePatientFaceImage() {
    const faceImage = document.getElementById('patient_face_image').value;
    if (!faceImage) {
        alert('Foto wajah pasien harus diambil atau diunggah!');
        return false;
    }
    return true;
}

const patientVideo = document.getElementById('patientVideo');
const patientCanvas = document.getElementById('patientCanvas');
const patientPreview = document.getElementById('patientPreview');
const patientCaptureBtn = document.getElementById('patientCaptureBtn');
const patientUploadImage = document.getElementById('patientUploadImage');
const patientFaceImageInput = document.getElementById('patient_face_image');

navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        patientVideo.style.display = 'block';
        patientCaptureBtn.style.display = 'block';
        patientVideo.srcObject = stream;
    })
    .catch(err => {
        console.error('Error accessing webcam:', err);
        patientVideo.style.display = 'none';
        patientCaptureBtn.style.display = 'none';
    });

patientCaptureBtn.addEventListener('click', () => {
    patientCanvas.width = patientVideo.videoWidth;
    patientCanvas.height = patientVideo.videoHeight;
    patientCanvas.getContext('2d').drawImage(patientVideo, 0, 0);
    const dataUrl = patientCanvas.toDataURL('image/jpeg');
    patientPreview.src = dataUrl;
    patientPreview.style.display = 'block';
    patientFaceImageInput.value = dataUrl;
});

patientUploadImage.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            patientPreview.src = e.target.result;
            patientPreview.style.display = 'block';
            patientFaceImageInput.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('toggleFormBtn').addEventListener('click', function () {
    const formSection = document.getElementById('addPatientFormSection');
    const formWrapper = document.querySelector('.form-wrapper');
    if (formSection.style.maxHeight === '0px' || formSection.style.maxHeight === '') {
        formSection.style.display = 'block';
        const fullHeight = formWrapper.scrollHeight + 20;
        setTimeout(() => {
            formSection.style.maxHeight = fullHeight + 'px';
            formSection.classList.add('active');
            document.getElementById('patient_name').focus();
        }, 10);
    } else {
        formSection.classList.remove('active');
        setTimeout(() => {
            formSection.style.maxHeight = '0';
            setTimeout(() => {
                formSection.style.display = 'none';
            }, 300);
        }, 10);
    }
});

document.querySelectorAll('.shake-on-focus').forEach(input => {
    input.addEventListener('focus', function () {
        console.log('Input focused:', this.id);
    });
});
</script>
@endsection