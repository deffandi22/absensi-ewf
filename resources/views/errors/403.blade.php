<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center p-5">
                    <h1 class="fw-bold text-danger">403</h1>
                    <h4 class="fw-bold mb-3">Akses Ditolak</h4>
                    <p class="text-muted">
                        Anda tidak memiliki hak akses untuk membuka halaman ini.
                    </p>

                    <a href="{{ url()->previous() }}" class="btn btn-primary rounded-pill px-4">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>