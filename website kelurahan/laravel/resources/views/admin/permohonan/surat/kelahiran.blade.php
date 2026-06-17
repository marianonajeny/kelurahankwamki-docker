@extends('admin.permohonan.surat._layout-resmi')

@section('surat-content')
<div class="judul-surat">
    <h3>Surat Keterangan Kelahiran</h3>
</div>
<div class="nomor-surat">
    <p>Nomor : {{ $nomor_surat }}</p>
</div>
<div class="isi">
    <p>Kepala Kelurahan Kwamki dengan ini menerangkan bahwa :</p>
    <table class="data-table">
        <tr><td>Nama Lengkap Anak</td><td>: {{ $permohonan->nama }}</td></tr>
        <tr><td>Tempat &amp; Tanggal Lahir</td><td>: {{ $tempat_tanggal_lahir ?? '—' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>: {{ $permohonan->jenis_kelamin ?? '—' }}</td></tr>
        <tr><td>Anak Ke</td><td>: {{ $permohonan->anak_ke ?? '—' }}</td></tr>
        <tr><td>Nama Lengkap Ayah</td><td>: {{ $permohonan->nama_ayah ?? '—' }}</td></tr>
        <tr><td>Nomor Induk Kependudukan Ayah</td><td>: {{ $permohonan->nik_ayah ?? '—' }}</td></tr>
        <tr><td>Nama Lengkap Ibu</td><td>: {{ $permohonan->nama_ibu ?? '—' }}</td></tr>
        <tr><td>Nomor Induk Kependudukan Ibu</td><td>: {{ $permohonan->nik_ibu ?? '—' }}</td></tr>
        <tr><td>Agama</td><td>: {{ $permohonan->agama ?? '—' }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $alamat_teks }}</td></tr>
        <tr><td>Kelurahan</td><td>: Kwamki</td></tr>
        <tr><td>Distrik</td><td>: Mimika Baru</td></tr>
        <tr><td>Kabupaten</td><td>: Mimika</td></tr>
        <tr><td>Provinsi</td><td>: Papua Tengah</td></tr>
    </table>
    <p>Demikian Surat Keterangan ini dibuat atas dasar yang sebenarnya dan dapat dipergunakan sebagaimana mestinya.</p>
</div>
@endsection
