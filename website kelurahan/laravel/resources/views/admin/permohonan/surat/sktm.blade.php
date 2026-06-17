@extends('admin.permohonan.surat._layout-resmi')

@section('surat-content')
<div class="judul-surat">
    <h3>Surat Keterangan Tidak Mampu</h3>
</div>
<div class="nomor-surat">
    <p>Nomor : {{ $nomor_surat }}</p>
</div>
<div class="isi">
    <p>Kepala Kelurahan Kwamki dengan ini menerangkan bahwa :</p>
    <table class="data-table">
        <tr><td>Nama Lengkap</td><td>: {{ $permohonan->nama }}</td></tr>
        <tr><td>Tempat &amp; Tanggal Lahir</td><td>: {{ $tempat_tanggal_lahir ?? '—' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>: {{ $permohonan->jenis_kelamin ?? '—' }}</td></tr>
        <tr><td>Agama</td><td>: {{ $permohonan->agama ?? '—' }}</td></tr>
        <tr><td>Status Perkawinan</td><td>: {{ $permohonan->status_perkawinan ?? '—' }}</td></tr>
        <tr><td>Kewarganegaraan</td><td>: INDONESIA</td></tr>
        <tr><td>Pekerjaan</td><td>: {{ $permohonan->pekerjaan ?? '—' }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $alamat_teks }}</td></tr>
        <tr><td>Kelurahan</td><td>: Kwamki</td></tr>
        <tr><td>Distrik</td><td>: Mimika Baru</td></tr>
        <tr><td>Kabupaten</td><td>: Mimika</td></tr>
        <tr><td>Nomor KTP</td><td>: {{ $permohonan->nik }}</td></tr>
    </table>
    <p>Yang bersangkutan adalah benar-benar penduduk Kelurahan Kwamki. Dan benar warga yang Tidak Mampu surat keterangan ini diberikan untuk melengkapi berkas pengurusan {{ $permohonan->keperluan }}.</p>
    <p>Demikian Surat Keterangan ini di buat dan dapat dipergunakan sebagaimana mestinya.</p>
</div>
@endsection
