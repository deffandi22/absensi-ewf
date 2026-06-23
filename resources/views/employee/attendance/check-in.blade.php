@extends('layouts.employee')

@section('title', 'Check-in')
@section('page-title', 'Check-in Karyawan')

@section('content')
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Check-in Karyawan</h3>
        <p class="text-muted mb-0">
            Ambil foto realtime dan izinkan lokasi untuk menyimpan absensi masuk.
        </p>
    </div>

    @if (session('warning'))
        <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Peringatan Lokasi
            </div>
            <div>
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-x-circle-fill me-1"></i>
                Gagal Check-in
            </div>
            <div>
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card dashboard-card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Kamera Realtime</h5>

                    <div class="camera-box mb-3">
                        <video id="video" autoplay playsinline></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <img id="preview" style="display: none;" alt="Preview Foto">
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-primary rounded-pill px-4" id="startCamera">
                            <i class="bi bi-camera-video me-1"></i>
                            Buka Kamera
                        </button>

                        <button type="button" class="btn btn-primary rounded-pill px-4" id="capturePhoto" disabled>
                            <i class="bi bi-camera-fill me-1"></i>
                            Ambil Foto
                        </button>

                        <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="retakePhoto" style="display: none;">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Ambil Ulang
                        </button>
                    </div>

                    <div class="alert alert-warning rounded-4 mt-4 mb-0 small">
                        Foto harus diambil langsung melalui kamera. Sistem tidak menyediakan upload dari galeri.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card dashboard-card mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Karyawan</h5>

                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">Nama</span>
                        <span class="fw-semibold text-end">{{ $user->name }}</span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">Divisi</span>
                        <span class="fw-semibold text-end">{{ $user->division->division_name ?? '-' }}</span>
                    </div>

                    <div class="d-flex justify-content-between py-3 gap-3">
                        <span class="text-muted">Tanggal</span>
                        <span class="fw-semibold">{{ now('Asia/Jakarta')->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="card dashboard-card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Lokasi</h5>

                    <div class="alert alert-info rounded-4 border-0 small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Pastikan lokasi Anda berada dalam radius kantor sebelum menyimpan check-in.
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Latitude</small>
                        <strong id="latitudeText">-</strong>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block">Longitude</small>
                        <strong id="longitudeText">-</strong>
                    </div>

                    <button type="button" class="btn btn-outline-primary rounded-pill px-4 w-100" id="getLocation">
                        <i class="bi bi-geo-alt-fill me-1"></i>
                        Ambil Lokasi
                    </button>

                    <form action="{{ route('employee.checkin.store') }}" method="POST" class="mt-4" id="checkInForm">
                        @csrf

                        <input type="hidden" name="photo" id="photoInput">
                        <input type="hidden" name="latitude" id="latitudeInput">
                        <input type="hidden" name="longitude" id="longitudeInput">

                        <button type="submit" class="btn btn-primary rounded-pill px-4 w-100" id="submitCheckIn" disabled>
                            <i class="bi bi-save-fill me-1"></i>
                            Simpan Check-in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .camera-box {
            width: 100%;
            background: #020617;
            border-radius: 22px;
            overflow: hidden;
            min-height: 360px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .camera-box video,
        .camera-box img {
            width: 100%;
            height: 360px;
            object-fit: cover;
        }

        @media (max-width: 576px) {
            .camera-box {
                min-height: 280px;
            }

            .camera-box video,
            .camera-box img {
                height: 280px;
            }
        }
    </style>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('preview');

        const startCameraBtn = document.getElementById('startCamera');
        const capturePhotoBtn = document.getElementById('capturePhoto');
        const retakePhotoBtn = document.getElementById('retakePhoto');
        const getLocationBtn = document.getElementById('getLocation');
        const submitCheckInBtn = document.getElementById('submitCheckIn');

        const photoInput = document.getElementById('photoInput');
        const latitudeInput = document.getElementById('latitudeInput');
        const longitudeInput = document.getElementById('longitudeInput');

        const latitudeText = document.getElementById('latitudeText');
        const longitudeText = document.getElementById('longitudeText');

        let stream = null;

        function checkFormReady() {
            submitCheckInBtn.disabled = !(photoInput.value && latitudeInput.value && longitudeInput.value);
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(function (track) {
                    track.stop();
                });

                stream = null;
            }
        }

        async function openCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 720 },
                        height: { ideal: 720 }
                    },
                    audio: false
                });

                video.srcObject = stream;
                video.style.display = 'block';
                preview.style.display = 'none';

                capturePhotoBtn.disabled = false;
                capturePhotoBtn.style.display = 'inline-block';

                retakePhotoBtn.style.display = 'none';
                startCameraBtn.disabled = true;
            } catch (error) {
                alert('Kamera tidak dapat dibuka. Pastikan izin kamera sudah diberikan dan gunakan browser yang mendukung kamera.');
                startCameraBtn.disabled = false;
            }
        }

        startCameraBtn.addEventListener('click', async function () {
            await openCamera();
        });

        capturePhotoBtn.addEventListener('click', function () {
            if (!video.videoWidth || !video.videoHeight) {
                alert('Kamera belum siap. Tunggu sebentar lalu coba lagi.');
                return;
            }

            const maxWidth = 720;
            const scale = maxWidth / video.videoWidth;

            canvas.width = maxWidth;
            canvas.height = video.videoHeight * scale;

            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/jpeg', 0.75);

            photoInput.value = imageData;
            preview.src = imageData;

            preview.style.display = 'block';
            video.style.display = 'none';

            capturePhotoBtn.style.display = 'none';
            retakePhotoBtn.style.display = 'inline-block';

            stopCamera();
            checkFormReady();
        });

        retakePhotoBtn.addEventListener('click', async function () {
            photoInput.value = '';

            preview.src = '';
            preview.style.display = 'none';

            video.style.display = 'block';

            capturePhotoBtn.disabled = true;
            capturePhotoBtn.style.display = 'inline-block';

            retakePhotoBtn.style.display = 'none';
            startCameraBtn.disabled = true;

            checkFormReady();

            await openCamera();
        });

        getLocationBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung fitur lokasi.');
                return;
            }

            getLocationBtn.disabled = true;
            getLocationBtn.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Mengambil Lokasi...';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    latitudeInput.value = latitude;
                    longitudeInput.value = longitude;

                    latitudeText.innerText = latitude;
                    longitudeText.innerText = longitude;

                    getLocationBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Lokasi Berhasil Diambil';
                    checkFormReady();
                },
                function () {
                    alert('Lokasi tidak dapat diambil. Pastikan izin lokasi sudah diberikan.');
                    getLocationBtn.disabled = false;
                    getLocationBtn.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Ambil Lokasi';
                    checkFormReady();
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

        document.getElementById('checkInForm').addEventListener('submit', function (e) {
            if (!photoInput.value || !latitudeInput.value || !longitudeInput.value) {
                e.preventDefault();
                alert('Foto dan lokasi wajib diambil sebelum menyimpan check-in.');
                return;
            }

            submitCheckInBtn.disabled = true;
            submitCheckInBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';
        });

        window.addEventListener('beforeunload', function () {
            stopCamera();
        });
    </script>
@endsection