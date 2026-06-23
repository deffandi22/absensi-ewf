<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi EWF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card border-0 shadow-sm rounded-4" style="max-width: 460px; width: 100%;">
        <div class="card-body p-5 text-center">
            <img src="{{ asset('images/logo-polos.png') }}" alt="Logo EWF" style="width: 92px; height: 92px; object-fit: contain;" class="mb-3">
            <h3 class="fw-bold mb-2">Sistem Absensi EWF</h3>
            <p class="text-muted mb-4">Silakan masuk untuk menggunakan sistem absensi karyawan.</p>
            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4" style="background:#f97316;border-color:#f97316;">
                Masuk ke Sistem
            </a>
        </div>
    </div>
</body>
</html>
