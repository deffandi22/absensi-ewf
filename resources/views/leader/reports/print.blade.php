<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Absensi Divisi</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 0;
            padding: 32px;
            background: #ffffff;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 52%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            opacity: 0.06;
            text-align: center;
            pointer-events: none;
        }

        .watermark img {
            width: 560px;
            max-width: 80vw;
            filter: blur(1.2px);
        }

        .report-wrapper {
            position: relative;
            z-index: 2;
        }

        .print-actions {
            margin-bottom: 18px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            border: none;
            padding: 10px 16px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
        }

        .btn-print {
            background: #111827;
            color: #ffffff;
        }

        .btn-back {
            background: #e5e7eb;
            color: #111827;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 3px solid #111827;
            padding-bottom: 18px;
            margin-bottom: 18px;
        }

        .header img {
            width: 86px;
            height: 86px;
            object-fit: contain;
        }

        .company-title {
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
        }

        .company-subtitle {
            font-size: 14px;
            margin: 4px 0 0;
            color: #374151;
        }

        .report-title {
            text-align: center;
            margin: 24px 0 18px;
        }

        .report-title h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .report-title p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #4b5563;
        }

        .meta-box {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 22px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .meta-item {
            display: flex;
            border-bottom: 1px dashed #d1d5db;
            padding-bottom: 6px;
        }

        .meta-label {
            width: 145px;
            font-weight: 700;
        }

        .meta-value {
            flex: 1;
        }

        .summary {
            margin: 18px 0;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .summary-card {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.92);
        }

        .summary-card small {
            color: #6b7280;
            display: block;
            margin-bottom: 4px;
            font-size: 11px;
        }

        .summary-card strong {
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            background: rgba(255, 255, 255, 0.9);
        }

        th {
            background: #111827;
            color: #ffffff;
            padding: 9px 7px;
            border: 1px solid #111827;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        td {
            padding: 8px 7px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        tr:nth-child(even):not(.row-danger) td {
            background: rgba(249, 250, 251, 0.92);
        }

        .row-danger td {
            background: #fee2e2 !important;
            color: #7f1d1d !important;
        }

        .badge-rejected,
        .badge-late,
        .badge-success,
        .badge-warning,
        .badge-secondary {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-rejected {
            background: #111827 !important;
            color: #ffffff !important;
        }

        .badge-late {
            background: #dc2626 !important;
            color: #ffffff !important;
        }

        .badge-success {
            background: #dcfce7 !important;
            color: #166534 !important;
        }

        .badge-warning {
            background: #fef3c7 !important;
            color: #92400e !important;
        }

        .badge-secondary {
            background: #e5e7eb !important;
            color: #374151 !important;
        }

        .small {
            font-size: 11px;
            color: #6b7280;
        }

        .note-danger {
            display: block;
            margin-top: 6px;
            font-size: 11px;
            color: #7f1d1d;
            font-weight: 700;
            line-height: 1.35;
        }

        .signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 48px;
            font-size: 13px;
        }

        .signature-box {
            width: 260px;
            text-align: center;
        }

        .signature-space {
            height: 70px;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                padding: 18px;
            }

            .print-actions {
                display: none !important;
            }

            .row-danger td {
                background: #fee2e2 !important;
                color: #7f1d1d !important;
            }

            th {
                background: #111827 !important;
                color: #ffffff !important;
            }

            @page {
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('images/logo-ewf.png') }}" alt="Watermark Logo">
    </div>

    <div class="report-wrapper">
        <div class="print-actions">
            <a href="/leader/reports" class="btn btn-back">
                Kembali
            </a>

            <button onclick="window.print()" class="btn btn-print">
                Cetak / Save PDF
            </button>
        </div>

        <div class="header">
            <img src="{{ asset('images/logo-ewf.png') }}" alt="Logo Perusahaan">

            <div>
                <h1 class="company-title">
                    PT. Equity World Futures Surabaya
                </h1>
                <p class="company-subtitle">
                    Laporan Absensi Karyawan Divisi
                </p>
                <p class="company-subtitle">
                    Dicetak pada: {{ now('Asia/Jakarta')->format('d M Y H:i') }} WIB
                </p>
            </div>
        </div>

        <div class="report-title">
            <h2>Laporan Absensi Divisi</h2>
            <p>
                Divisi:
                <strong>{{ $leader->division->division_name ?? 'Divisi Tidak Diketahui' }}</strong>
            </p>
            <p>
                Periode:
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Awal Data' }}
                sampai
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Akhir Data' }}
            </p>
        </div>

        <div class="meta-box">
            <div class="meta-item">
                <div class="meta-label">ID Karyawan</div>
                <div class="meta-value">
                    : {{ request('employee_id') ?: 'Semua Karyawan' }}
                </div>
            </div>

            <div class="meta-item">
                <div class="meta-label">Pencarian</div>
                <div class="meta-value">
                    : {{ request('search') ?: '-' }}
                </div>
            </div>

            <div class="meta-item">
                <div class="meta-label">Divisi</div>
                <div class="meta-value">
                    : {{ $leader->division->division_name ?? 'Divisi Tidak Diketahui' }}
                </div>
            </div>

            <div class="meta-item">
                <div class="meta-label">Status</div>
                <div class="meta-value">
                    :
                    @if (request('status') === 'hadir')
                        Hadir
                    @elseif (request('status') === 'terlambat')
                        Terlambat
                    @elseif (request('status') === 'selesai')
                        Selesai
                    @elseif (request('status') === 'belum_checkout')
                        Belum Check-out
                    @elseif (request('status') === 'ditolak')
                        Ditolak
                    @else
                        Semua Status
                    @endif
                </div>
            </div>
        </div>

        @php
            $totalData = $attendances->count();

            $totalDitolak = $attendances->filter(function ($attendance) {
                return ($attendance->verification_status ?? 'valid') === 'ditolak';
            })->count();

            $totalTerlambat = $attendances->filter(function ($attendance) {
                return ($attendance->verification_status ?? 'valid') !== 'ditolak'
                    && $attendance->status === 'terlambat';
            })->count();

            $totalSelesai = $attendances->filter(function ($attendance) {
                return ($attendance->verification_status ?? 'valid') !== 'ditolak'
                    && $attendance->check_out_time;
            })->count();

            $totalBelumCheckout = $attendances->filter(function ($attendance) {
                return ($attendance->verification_status ?? 'valid') !== 'ditolak'
                    && $attendance->check_in_time
                    && !$attendance->check_out_time
                    && $attendance->status !== 'terlambat';
            })->count();
        @endphp

        <div class="summary">
            <div class="summary-card">
                <small>Total Data</small>
                <strong>{{ $totalData }}</strong>
            </div>

            <div class="summary-card">
                <small>Selesai</small>
                <strong>{{ $totalSelesai }}</strong>
            </div>

            <div class="summary-card">
                <small>Terlambat</small>
                <strong>{{ $totalTerlambat }}</strong>
            </div>

            <div class="summary-card">
                <small>Belum Check-out</small>
                <strong>{{ $totalBelumCheckout }}</strong>
            </div>

            <div class="summary-card">
                <small>Ditolak</small>
                <strong>{{ $totalDitolak }}</strong>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Karyawan</th>
                    <th>Nama Karyawan</th>
                    <th>Tanggal</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($attendances as $attendance)
                    <tr class="{{ (($attendance->verification_status ?? 'valid') === 'ditolak') || $attendance->status === 'terlambat' ? 'row-danger' : '' }}">
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $attendance->user->employee->id ?? '-' }}
                        </td>

                        <td>
                            <strong>{{ $attendance->user->name ?? '-' }}</strong><br>
                            <small class="small">{{ $attendance->user->email ?? '-' }}</small>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                        </td>

                        <td>
                            {{ $attendance->check_in_time ? $attendance->check_in_time . ' WIB' : '-' }}
                        </td>

                        <td>
                            {{ $attendance->check_out_time ? $attendance->check_out_time . ' WIB' : '-' }}
                        </td>

                        <td>
                            @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                                <span class="badge-rejected">Ditolak</span>

                                <span class="note-danger">
                                    {{ $attendance->rejection_reason ?? 'Absensi ditolak' }}

                                    @if ($attendance->rejectedBy)
                                        <br>Oleh: {{ $attendance->rejectedBy->name }}
                                    @endif

                                    @if ($attendance->rejected_at)
                                        <br>{{ \Carbon\Carbon::parse($attendance->rejected_at)->format('d M Y H:i') }} WIB
                                    @endif
                                </span>
                            @elseif ($attendance->status === 'terlambat')
                                <span class="badge-late">Terlambat</span>
                            @elseif ($attendance->check_out_time)
                                <span class="badge-success">Selesai</span>
                            @elseif ($attendance->check_in_time)
                                <span class="badge-warning">Belum Check-out</span>
                            @else
                                <span class="badge-secondary">Belum Check-in</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 24px;">
                            Tidak ada data absensi pada filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signature">
            <div class="signature-box">
                <p>Surabaya, {{ now('Asia/Jakarta')->format('d M Y') }}</p>
                <p>Ketua Ruangan</p>

                <div class="signature-space"></div>

                <p><strong>{{ auth()->user()->name ?? 'Ketua Ruangan' }}</strong></p>
            </div>
        </div>
    </div>
</body>
</html>