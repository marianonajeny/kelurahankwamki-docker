<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; margin: 40px; }
        .kop { text-align: center; margin-bottom: 24px; }
        .kop h1 { font-size: 14pt; margin: 0; font-weight: bold; text-transform: uppercase; }
        .kop h2 { font-size: 12pt; margin: 2px 0; font-weight: bold; }
        .kop p { margin: 2px 0; font-size: 11pt; }
        .garis { border-bottom: 3px double #000; margin: 8px auto 0; width: 80%; }
        .nomor { margin: 20px 0; }
        .nomor table { width: 100%; }
        .nomor td { vertical-align: top; padding: 2px 0; }
        .isi { text-align: justify; margin: 16px 0; }
        .isi p { margin: 8px 0; text-indent: 36px; }
        .data-table { width: 100%; margin: 12px 0 12px 36px; border-collapse: collapse; }
        .data-table td { padding: 4px 8px; vertical-align: top; }
        .data-table td:first-child { width: 140px; }
        .ttd { margin-top: 48px; text-align: right; }
        .ttd p { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="kop">
        <h1>Pemerintah Kabupaten Mimika</h1>
        <h2>Kecamatan Mimika Barat</h2>
        <h2>Kelurahan Kwamki</h2>
        <p>Jl. Kwamki No. 1, Kwamki, Mimika Barat — Telp. (0901) — Kode Pos 99962</p>
        <div class="garis"></div>
    </div>

    @yield('surat-content')

    <div class="ttd">
        <p>Kwamki, {{ $tanggal_surat_teks }}</p>
        <p>{{ $jabatan_penandatangan ?? 'Lurah Kwamki' }},</p>
        @if(!empty($ttd_gambar_src))
            <img src="{{ $ttd_gambar_src }}" alt="TTD" style="max-height: 80px; margin: 8px 0; display: block; margin-left: auto;">
        @else
            <br><br><br>
        @endif
        <p><strong>{{ $nama_penandatangan ?? '(_____________________)' }}</strong></p>
        <p>NIP. {{ $nip_penandatangan ?? '—' }}</p>
    </div>
</body>
</html>
