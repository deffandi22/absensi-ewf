<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak | Sistem Absensi EWF</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: #f1f5f9;">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger-subtle text-danger fw-bold"
                             style="width: 76px; height: 76px; font-size: 34px;">
                            !
                        </div>
                    </div>

                    <h2 class="fw-bold mb-3">Akses Ditolak</h2>

                    <p class="text-muted mb-3">
                        Sistem hanya dapat diakses melalui jaringan kantor
                        <strong>PT. Equity World Futures Surabaya</strong>.
                    </p>

                    <p class="text-muted small mb-4">
                        IP perangkat/jaringan Anda saat ini:
                        <strong>{{ $clientIp ?? '-' }}</strong>
                    </p>

                    <div class="alert alert-warning text-start small rounded-4">
                        Silakan hubungkan perangkat ke WiFi kantor terlebih dahulu,
                        kemudian buka kembali sistem absensi.
                    </div>

                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                        Coba Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>