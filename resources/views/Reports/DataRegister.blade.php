<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lembar Pencatatan PTM</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
        }

        /* Kunci page break: setiap .page akan mulai di halaman baru,
           KECUALI halaman terakhir (:last-child) */
        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #222;
            padding-bottom: 8px;
        }

        .page-header .title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .page-header .subtitle {
            font-size: 10px;
            margin-top: 4px;
            color: #444;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 10px 0;
            padding: 4px 8px;
            background: #f2f2f2;
            border-left: 4px solid #333;
        }

        /* --- Halaman 1: Identitas, ditampilkan sebagai daftar info --- */
        table.identity {
            width: 100%;
            border-collapse: collapse;
        }

        table.identity td {
            padding: 5px 4px;
            font-size: 10px;
            vertical-align: top;
        }

        table.identity td.label {
            width: 30%;
            font-weight: bold;
        }

        table.identity td.sep {
            width: 3%;
        }

        /* --- Halaman 2 & 3: tabel riwayat kunjungan --- */
        table.history {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.history th,
        table.history td {
            border: 1px solid #333;
            padding: 5px 6px;
            font-size: 9px;
            text-align: center;
        }

        table.history th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .muted {
            color: #666;
            font-size: 8.5px;
            margin-top: 10px;
        }

        .badge-normal { color: #1a7a1a; font-weight: bold; }
        .badge-warning { color: #b8860b; font-weight: bold; }
        .badge-danger { color: #b00000; font-weight: bold; }
    </style>
</head>

<body>
    @php
        $patient = ($riwayat && count($riwayat) > 0) ? $riwayat[0] : null;

        $display = function ($value, $fallback = '-') {
            return isset($value) && $value !== '' ? $value : $fallback;
        };

        $gender = strtolower($patient->JENIS_KLMIN ?? $patient->jenis_kelamin ?? '');
        $genderLabel = in_array($gender, ['l', 'laki-laki', 'laki laki', 'male', '1'], true)
            ? 'Laki-laki'
            : (in_array($gender, ['p', 'perempuan', 'female', '2'], true) ? 'Perempuan' : '-');

        // Klasifikasi IMT sederhana untuk badge di halaman obesitas
        $imtStatus = function ($imt) {
            if (!$imt) return ['label' => '-', 'class' => ''];
            if ($imt < 18.5) return ['label' => 'Kurus', 'class' => 'badge-warning'];
            if ($imt < 25) return ['label' => 'Normal', 'class' => 'badge-normal'];
            if ($imt < 27) return ['label' => 'Gemuk', 'class' => 'badge-warning'];
            return ['label' => 'Obesitas', 'class' => 'badge-danger'];
        };

        // Klasifikasi tekanan darah sederhana untuk badge di halaman hipertensi
        $bpStatus = function ($sistolik, $diastolik) {
            if (!$sistolik || !$diastolik) return ['label' => '-', 'class' => ''];
            if ($sistolik >= 140 || $diastolik >= 90) return ['label' => 'Hipertensi', 'class' => 'badge-danger'];
            if ($sistolik >= 130 || $diastolik >= 85) return ['label' => 'Waspada', 'class' => 'badge-warning'];
            return ['label' => 'Normal', 'class' => 'badge-normal'];
        };
    @endphp

    {{-- ======================= HALAMAN 1: IDENTITAS PASIEN ======================= --}}
    <div class="page">
        <div class="page-header">
            <p class="title">Lembar Pencatatan Faktor Risiko PTM</p>
            <div class="subtitle">Halaman 1 - Identitas Pasien</div>
        </div>

        <p class="section-title">Data Identitas</p>

        <table class="identity">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="sep">:</td>
                <td>{{ $display($patient->NAMA_LGKP ?? null) }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="sep">:</td>
                <td>{{ $display($patient->NIK ?? null) }}</td>
            </tr>
            <tr>
                <td class="label">No. KMS FR-PTM</td>
                <td class="sep">:</td>
                <td>{{ $display($patient->NO_MR ?? null) }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="sep">:</td>
                <td>{{ $genderLabel }}</td>
            </tr>
            <tr>
                <td class="label">Umur</td>
                <td class="sep">:</td>
                <td>{{ $display($patient->umur ?? null) }} tahun</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="sep">:</td>
                <td>{{ $display($patient->ALAMAT ?? $patient->alamat ?? null) }}</td>
            </tr>
            <tr>
                <td class="label">Riwayat PTM Diri Sendiri</td>
                <td class="sep">:</td>
                <td>{{ $display($patient->riwayat_ptm_diri ?? null) }}</td>
            </tr>
            <tr>
                <td class="label">Riwayat PTM Keluarga</td>
                <td class="sep">:</td>
                <td>{{ $display($patient->riwayat_ptm_keluarga ?? null) }}</td>
            </tr>
        </table>
    </div>

    {{-- ======================= HALAMAN 2: DATA OBESITAS ======================= --}}
    <div class="page">
        <div class="page-header">
            <p class="title">Lembar Pencatatan Faktor Risiko PTM</p>
            <div class="subtitle">Halaman 2 - Riwayat Pengukuran Obesitas</div>
        </div>

        <p class="section-title">Data Antropometri</p>

        <table class="history">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>BB (kg)</th>
                    <th>TB (cm)</th>
                    <th>LP (cm)</th>
                    <th>IMT</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $index => $row)
                    @php $status = $imtStatus($row->imt ?? null); @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $display($row->tanggal ?? $row->created_at ?? null) }}</td>
                        <td>{{ $display($row->berat_badan ?? $row->bb ?? null) }}</td>
                        <td>{{ $display($row->tinggi_badan ?? $row->tb ?? null) }}</td>
                        <td>{{ $display($row->lingkar_pinggang ?? $row->lingkar_perut ?? null) }}</td>
                        <td>{{ $display($row->imt ?? null) }}</td>
                        <td class="{{ $status['class'] }}">{{ $status['label'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Belum ada data pengukuran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="muted">
            Klasifikasi IMT: Kurus (&lt;18.5), Normal (18.5-24.9), Gemuk (25-26.9), Obesitas (&ge;27).
        </p>
    </div>

    {{-- ======================= HALAMAN 3: DATA HIPERTENSI ======================= --}}
    <div class="page">
        <div class="page-header">
            <p class="title">Lembar Pencatatan Faktor Risiko PTM</p>
            <div class="subtitle">Halaman 3 - Riwayat Tekanan Darah</div>
        </div>

        <p class="section-title">Data Tekanan Darah</p>

        <table class="history">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Sistolik</th>
                    <th>Diastolik</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $index => $row)
                    @php $status = $bpStatus($row->sistolik ?? null, $row->tekanan_diastolik ?? null); @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $display($row->tanggal ?? $row->created_at ?? null) }}</td>
                        <td>{{ $display($row->sistolik ?? null) }}</td>
                        <td>{{ $display($row->tekanan_diastolik ?? null) }}</td>
                        <td class="{{ $status['class'] }}">{{ $status['label'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada data pengukuran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="muted">
            Klasifikasi: Normal (&lt;130/85), Waspada (130-139/85-89), Hipertensi (&ge;140/90).
        </p>
    </div>

</body>

</html>
