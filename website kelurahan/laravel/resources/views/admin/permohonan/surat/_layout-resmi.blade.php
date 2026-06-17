<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { size: 210mm 297mm; margin: 12mm 18mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.5; color: #000; margin: 0; }
        .kop { display: table; width: 100%; border-bottom: 3px solid #000; padding-bottom: 6px; margin-bottom: 6px; }
        .kop-logo-cell { display: table-cell; width: 95px; vertical-align: middle; text-align: center; }
        .kop-logo { width: 85px; height: auto; max-height: 95px; }
        .kop-text-cell { display: table-cell; vertical-align: middle; text-align: center; width: auto; }
        .kop-spacer-cell { display: table-cell; width: 95px; }
        .kop h1 { font-size: 14pt; margin: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop h2 { font-size: 12pt; margin: 1px 0; font-weight: bold; text-transform: uppercase; }
        .kop p { margin: 2px 0 0; font-size: 11pt; font-weight: normal; }
        .kop .alamat { font-style: italic; }
        .judul-surat { text-align: center; margin: 6px 0 4px; }
        .judul-surat h3 { font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; margin: 0; }
        .nomor-surat { text-align: center; margin: 4px 0 10px; }
        .isi { margin: 8px 0; }
        .isi p { margin: 4px 0; text-indent: 0; text-align: justify; }
        .data-table { width: 100%; margin: 6px 0 6px 24px; border-collapse: collapse; table-layout: fixed; }
        .data-table td { padding: 1px 0; vertical-align: top; }
        .data-table td:first-child { width: 42%; padding-right: 6px; }
        .data-table td:last-child { width: 58%; }
        .subjudul { font-weight: bold; margin: 8px 0 4px; }
        .ttd { margin-top: 18px; text-align: right; }
        .ttd p { margin: 2px 0; text-align: right; }
        .ttd .nama { font-weight: bold; text-decoration: underline; text-transform: uppercase; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="kop-logo-cell">
            @if(!empty($logo_kop_src))
                <img src="{{ $logo_kop_src }}" alt="Logo Kabupaten Mimika" class="kop-logo">
            @endif
        </div>
        <div class="kop-text-cell">
            <h1>Pemerintah Kabupaten Mimika</h1>
            <h2>Kelurahan Kwamki</h2>
            <h2>Distrik Mimika Baru</h2>
            <p class="alamat">Jl. C. Heatubun – Timika</p>
        </div>
        <div class="kop-spacer-cell"></div>
    </div>

    @yield('surat-content')

    <div class="ttd">
        <p>DI KELUARKAN DI : TIMIKA</p>
        <p>PADA TANGGAL : {{ $tanggal_surat_teks }}</p>
        <p style="margin-top: 12px;">KEPALA KELURAHAN KWAMKI</p>
        @if(!empty($ttd_gambar_src))
            <img src="{{ $ttd_gambar_src }}" alt="TTD" style="max-height: 55px; margin: 8px 0 2px; display: block; margin-left: auto;">
        @else
            <br><br><br>
        @endif
        <p class="nama">{{ $nama_penandatangan ?? '(_____________________)' }}</p>
        <p>NIP. {{ $nip_penandatangan ?? '—' }}</p>
    </div>
</body>
</html>
