<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Register Faktor Risiko PTM</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #111;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 8px;
            margin-top: 3px;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
            font-size: 7.5px;
        }

        .meta td {
            border: 0;
            padding: 1px 3px;
            vertical-align: top;
        }

        table.register {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .register th,
        .register td {
            border: 1px solid #222;
            padding: 2px 2px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .register th {
            font-size: 6.5px;
            font-weight: bold;
            line-height: 1.1;
            background: #f2f2f2;
        }

        .register td {
            height: 20px;
            font-size: 6.8px;
            line-height: 1.15;
        }

        .left {
            text-align: left !important;
        }

        .muted {
            color: #555;
        }

        .small {
            font-size: 6.2px;
        }

        .col-no { width: 2.5%; }
        .col-kms { width: 4.2%; }
        .col-nik { width: 7%; }
        .col-nama { width: 8%; }
        .col-alamat { width: 9%; }
        .col-sex { width: 2.5%; }
        .col-short { width: 3.5%; }
        .col-mid { width: 4.2%; }
        .col-wide { width: 5.2%; }
    </style>
</head>

<body>
    <div class="header">
        <p class="title">Register Deteksi Dini Faktor Risiko Penyakit Tidak Menular</p>
        <div class="subtitle">Posbindu PTM / FKTP - Lembar pencatatan pasien dan hasil skrining</div>
    </div>

    @php
        $patient = ($riwayat && count($riwayat) > 0) ? $riwayat[0] : null;
        $rows = $patient ? [$patient] : [];
        $emptyRows = $patient ? 8 : 15;

        $display = function ($value, $fallback = '') {
            return isset($value) && $value !== '' ? $value : $fallback;
        };

        $yesNo = function ($value) {
            if ($value === 'ya') {
                return 'Ya';
            }

            if ($value === 'tidak') {
                return 'Tidak';
            }

            return '';
        };

        $gender = strtolower($patient->JENIS_KLMIN ?? $patient->jenis_kelamin ?? '');
        $isMale = in_array($gender, ['l', 'laki-laki', 'laki laki', 'male', '1'], true);
        $isFemale = in_array($gender, ['p', 'perempuan', 'female', '2'], true);
    @endphp

    <table class="meta">
        <tr>
            <td style="width: 13%;">Puskesmas</td>
            <td style="width: 28%;">: ........................................................</td>
            <td style="width: 10%;">Desa/Kel.</td>
            <td style="width: 22%;">: ........................................</td>
            <td style="width: 8%;">Bulan</td>
            <td>: ................................</td>
        </tr>
        <tr>
            <td>Posbindu</td>
            <td>: ........................................................</td>
            <td>Kecamatan</td>
            <td>: ........................................</td>
            <td>Tahun</td>
            <td>: ................................</td>
        </tr>
    </table>

    <table class="register">
        <thead>
            <tr>
                <th class="col-no" rowspan="3">No</th>
                <th class="col-kms" rowspan="3">No. KMS<br>FR-PTM</th>
                <th class="col-nik" rowspan="3">NIK</th>
                <th class="col-nama" rowspan="3">Nama Peserta</th>
                <th class="col-alamat" rowspan="3">Alamat</th>
                <th colspan="2">Umur</th>
                <th colspan="2">Riwayat PTM</th>
                <th colspan="5">Faktor Risiko Perilaku</th>
                <th colspan="4">Pengukuran</th>
                <th colspan="4">Pemeriksaan</th>
                <th colspan="4">Deteksi Dini Lain</th>
                <th class="col-wide" rowspan="3">Konseling / Edukasi</th>
                <th class="col-wide" rowspan="3">Tindak Lanjut / Rujukan</th>
                <th class="col-wide" rowspan="3">Keterangan</th>
            </tr>
            <tr>
                <th class="col-sex" rowspan="2">L</th>
                <th class="col-sex" rowspan="2">P</th>
                <th class="col-mid" rowspan="2">Diri<br>Sendiri</th>
                <th class="col-mid" rowspan="2">Keluarga</th>
                <th class="col-short" rowspan="2">Merokok</th>
                <th class="col-short" rowspan="2">Kurang<br>Aktivitas</th>
                <th class="col-short" rowspan="2">Kurang<br>Sayur Buah</th>
                <th class="col-short" rowspan="2">Gula<br>Berlebih</th>
                <th class="col-short" rowspan="2">Alkohol</th>
                <th class="col-short" rowspan="2">BB<br>(kg)</th>
                <th class="col-short" rowspan="2">TB<br>(cm)</th>
                <th class="col-short" rowspan="2">IMT</th>
                <th class="col-short" rowspan="2">LP<br>(cm)</th>
                <th class="col-mid" colspan="2">Tekanan Darah</th>
                <th class="col-mid" colspan="2">Laboratorium</th>
                <th class="col-short" rowspan="2">IVA</th>
                <th class="col-short" rowspan="2">SADANIS</th>
                <th class="col-short" rowspan="2">Paru</th>
                <th class="col-short" rowspan="2">Indera</th>
            </tr>
            <tr>
                <th class="col-short">Sistolik</th>
                <th class="col-short">Diastolik</th>
                <th class="col-short">Gula<br>Darah</th>
                <th class="col-short">Kolesterol</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $display($row->NO_MR ?? null) }}</td>
                <td>{{ $display($row->NIK ?? null) }}</td>
                <td class="left">{{ $display($row->NAMA_LGKP ?? null) }}</td>
                <td class="left small">{{ $display($row->ALAMAT ?? $row->alamat ?? null) }}</td>
                <td>{{ $isMale ? $display($row->umur ?? null) : '' }}</td>
                <td>{{ $isFemale ? $display($row->umur ?? null) : '' }}</td>
                <td></td>
                <td></td>
                <td>{{ $yesNo($row->merokok ?? null) }}</td>
                <td>{{ $yesNo($row->aktivitas ?? null) }}</td>
                <td>{{ $yesNo($row->sayur ?? null) }}</td>
                <td>{{ $yesNo($row->gula ?? null) }}</td>
                <td>{{ $yesNo($row->alkohol ?? null) }}</td>
                <td>{{ $display($row->berat_badan ?? $row->bb ?? null) }}</td>
                <td>{{ $display($row->tinggi_badan ?? $row->tb ?? null) }}</td>
                <td>{{ $display($row->imt ?? null) }}</td>
                <td>{{ $display($row->lingkar_pinggang ?? $row->lingkar_perut ?? null) }}</td>
                <td>{{ $display($row->sistolik ?? null) }}</td>
                <td>{{ $display($row->tekanan_diastolik ?? null) }}</td>
                <td>{{ $display($row->gula_darah_sewaktu ?? $row->gula_darah_puasa ?? $row->gula_darah_2_jam_pp ?? null) }}</td>
                <td>{{ $display($row->kolesterol_total ?? null) }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @empty
            @endforelse

            @for($i = 0; $i < $emptyRows; $i++)
            <tr>
                <td>{{ $patient ? '' : $i + 1 }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @endfor
        </tbody>
    </table>

    <p class="small muted">
        Catatan: kolom mengikuti format register deteksi dini faktor risiko PTM. Kolom yang belum tersedia pada data aplikasi
        disiapkan sebagai ruang isian manual atau dapat dihubungkan ke query laporan berikutnya.
    </p>
</body>

</html>
