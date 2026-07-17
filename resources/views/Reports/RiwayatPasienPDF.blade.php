<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Riwayat Kesehatan Pasien</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 20px;
            color: #333;
        }

        .patient-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
        }

        .patient-info td {
            border: none;
            padding: 8px 12px;
        }

        .table-container {
            margin-top: 20px;
        }

        table.riwayat {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.riwayat thead {
            background-color: #343a40;
            color: white;
        }

        table.riwayat th {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
        }

        table.riwayat td {
            border: 1px solid #dee2e6;
            padding: 8px 10px;
            font-size: 10px;
            vertical-align: top;
        }

        table.riwayat tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            padding: 20px;
            font-style: italic;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            text-align: center;
            color: #6c757d;
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .kop-surat td {
            border: none;
            vertical-align: middle;
        }

        .kop-logo {
            width: 15%;
            text-align: right;
            padding-right: 10px;
        }

        .kop-logo img {
            max-width: 90px;
        }

        .kop-text {
            width: 70%;
            text-align: center;
        }

        .kop-text h5 {
            margin: 0;
            font-weight: bold;
            font-size: 13px;
        }

        .kop-text p {
            margin: 0;
            font-size: 10px;
        }

        .kop-spacer {
            width: 15%;
        }

        hr.kop-line {
            border: none;
            border-top: 3px solid #000;
            margin: 6px 0 12px 0;
        }
    </style>
</head>

<body>
    {{-- $patient didefinisikan di sini, PALING ATAS, sebelum dipakai di kop surat --}}
    @php $patient = ($riwayat && count($riwayat) > 0) ? $riwayat[0] : null; @endphp

    <!-- Kop Surat -->
    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('images/logo-dinkes.png') }}" />
            </td>
            <td class="kop-text">
                <h5>PEMERINTAH KABUPATEN BANYUWANGI</h5>
                <h5>DINAS KESEHATAN</h5>
                <h5>{{ $patient->fasyankes ?? 'PUSKESMAS SIMPUS' }}</h5>
                <p>{{ $unit->alamat ?? 'Jl. Contoh Alamat No. 1, Banyuwangi' }}</p>
            </td>
            <td class="kop-spacer"></td>
        </tr>
    </table>
    <hr class="kop-line" />

    <div class="header" style="text-align:center; margin-bottom: 15px;">
        <h1 style="font-size:16px; margin:0;">LAPORAN RIWAYAT KESEHATAN PASIEN</h1>
        <p style="margin: 5px 0;">Data Riwayat Pemeriksaan Penyakit Tidak Menular (Medical Record) Pasien</p>
    </div>

    @if($riwayat && count($riwayat) > 0)

    <table class="patient-info">
        <tr>
            <td style="width: 12%;"><strong>Nama</strong></td>
            <td style="width: 38%;">: {{ strtoupper($patient->NAMA_LGKP ?? '-') }}</td>
            <td style="width: 12%;"><strong>NIK</strong></td>
            <td style="width: 38%;">: {{ $patient->NIK ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>No MR</strong></td>
            <td>: {{ $patient->NO_MR ?? '-' }}</td>
            <td><strong>Alamat</strong></td>
            <td>: {{ $patient->ALAMAT ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tgl Lahir</strong></td>
            <td>: {{ $patient->TGL_LHR ?? '-' }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="table-container">
        <table class="riwayat">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tgl Kunjung</th>
                    <th style="width: 12%;">Sistol/Diastol</th>
                    <th style="width: 12%;">TB/BB/IMT</th>
                    <th style="width: 10%;">Nadi</th>
                    <th style="width: 10%;">Suhu</th>
                    <th style="width: 10%;">RR</th>
                    <th style="width: 19%;">Keluhan Utama</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $data->tanggal_skrining ? date('d/m/Y', strtotime($data->tanggal_skrining)) : '-' }}</td>
                    <td class="text-center"><strong>{{ $data->sistolik ?? '-' }}/{{ $data->tekanan_diastolik ?? '-' }}</strong></td>
                    <td class="text-center">{{ $data->tinggi_badan ?? '-' }}/{{ $data->berat_badan ?? '-' }}/{{ $data->imt ?? '-' }}</td>
                    <td class="text-center">{{ $data->nadi ?? '-' }}</td>
                    <td class="text-center">{{ $data->suhu ?? '-' }}&deg;C</td>
                    <td class="text-center">{{ $data->pernapasan ?? '-' }}</td>
                    <td>{{ $data->keluhan_utama ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="no-data">Tidak ada data riwayat kesehatan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh sistem pada {{ now()->format('d F Y H:i:s') }}</p>
    </div>
    @else
    <div class="no-data">
        <p>Tidak ada data riwayat kesehatan untuk ditampilkan</p>
    </div>
    @endif
</body>

</html>
