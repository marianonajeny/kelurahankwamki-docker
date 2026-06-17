@extends('admin.permohonan.surat._layout')

@section('surat-content')
<div class="nomor">
    <table>
        <tr><td style="width:55%">Nomor</td><td>: {{ $nomor_surat }}</td></tr>
        <tr><td>Lampiran</td><td>: —</td></tr>
        <tr><td>Perihal</td><td>: <strong>Surat Pengantar KTP</strong></td></tr>
    </table>
    <p style="text-align:right; margin-top:12px;">Kwamki, {{ $tanggal_surat_teks }}</p>
</div>
<div class="isi">
    <p>Yang bertanda tangan di bawah ini, Lurah Kwamki, Kecamatan Mimika Barat, Kabupaten Mimika, dengan ini menerangkan bahwa:</p>
    <table class="data-table">
        <tr><td>Nama</td><td>: {{ $permohonan->nama }}</td></tr>
        <tr><td>NIK</td><td>: {{ $permohonan->nik }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $permohonan->alamat }}</td></tr>
    </table>
    <p>Memerlukan surat pengantar untuk pengurusan Kartu Tanda Penduduk (KTP).</p>
    <p>Keperluan: <strong>{{ $permohonan->keperluan }}</strong>.</p>
    <p>Demikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
</div>
@endsection
