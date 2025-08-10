@extends('base')

@section('content')
<div class="card shadow-lg p-4 mx-auto" style="max-width: 400px;">
    <h3 class="text-center mb-4">Registrasi Perawat</h3>
    <form method="POST" action="{{ route('register') }}" onsubmit="return prepareFaceImage()">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
        </div>
        <div class="mb-3">
            <label for="face_image" class="form-label">Foto Wajah</label>
            <div class="d-flex flex-column align-items-center">
                <video id="video" width="100%" height="auto" autoplay style="display: none;"></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <img id="preview" class="img-fluid mb-2" style="max-height: 200px; display: none;">
                <input type="hidden" name="face_image" id="face_image">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="captureBtn">Ambil Foto</button>
                    <input type="file" id="uploadImage" accept="image/*" class="form-control" style="max-width: 200px;">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>
    <p class="text-center mt-3">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
</div>

<script>
function prepareFaceImage() {
    const faceImage = document.getElementById('face_image').value;
    if (!faceImage) {
        alert('Foto wajah harus diambil atau diunggah!');
        return false;
    }
    return true;
}

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const preview = document.getElementById('preview');
const captureBtn = document.getElementById('captureBtn');
const uploadImage = document.getElementById('uploadImage');
const faceImageInput = document.getElementById('face_image');

navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.style.display = 'block';
        captureBtn.style.display = 'block';
        video.srcObject = stream;
    })
    .catch(err => {
        console.error('Error accessing webcam:', err);
        video.style.display = 'none';
        captureBtn.style.display = 'none';
    });

captureBtn.addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg');
    preview.src = dataUrl;
    preview.style.display = 'block';
    faceImageInput.value = dataUrl;
});

uploadImage.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            faceImageInput.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection