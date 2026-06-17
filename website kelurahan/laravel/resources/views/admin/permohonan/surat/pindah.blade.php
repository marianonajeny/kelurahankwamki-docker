@extends('admin.permohonan.surat._layout-resmi')

@section('surat-content')
<div class="judul-surat">
    <h3>Surat Keterangan Pindah</h3>
</div>
<div class="nomor-surat">
    <p>Nomor : {{ $nomor_surat }}</p>
</div>
<div class="isi">
    <p>Kepala Kelurahan Kwamki dengan ini menerangkan bahwa :</p>
    <table class="data-table">
        <tr><td>Nama</td><td>: {{ $permohonan->nama }}</td></tr>
        <tr><td>Tempat &amp; Tanggal Lahir</td><td>: {{ $tempat_tanggal_lahir ?? '—' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>: {{ $permohonan->jenis_kelamin ?? '—' }}</td></tr>
        <tr><td>Agama</td><td>: {{ $permohonan->agama ?? '—' }}</td></tr>
        <tr><td>Status Perkawinan</td><td>: {{ $permohonan->status_perkawinan ?? '—' }}</td></tr>
        <tr><td>Kewarganegaraan</td><td>: Indonesia</td></tr>
        <tr><td>Pekerjaan</td><td>: {{ $permohonan->pekerjaan ?? '—' }}</td></tr>
        <tr><td>Alamat Asal</td><td>: {{ $alamat_teks }}</td></tr>
        <tr><td>Pendidikan</td><td>: {{ $permohonan->pendidikan ?? '—' }}</td></tr>
        <tr><td>N.I.K</td><td>: {{ $permohonan->nik }}</td></tr>
    </table>
    <p class="subjudul">Pindah ke : {{ $permohonan->kelurahan_tujuan ?? '..............................................................' }}</p>
    <table class="data-table">
        <tr><td>Desa / Kelurahan</td><td>: {{ $permohonan->kelurahan_tujuan ?? '—' }}</td></tr>
        <tr><td>Kecamatan</td><td>: {{ $permohonan->kecamatan_tujuan ?? 'Mimika Baru' }}</td></tr>
        <tr><td>Kota</td><td>: {{ $permohonan->kota_tujuan ?? 'Mimika' }}</td></tr>
        <tr><td>Provinsi</td><td>: {{ $permohonan->provinsi_tujuan ?? 'Papua Tengah' }}</td></tr>
        <tr><td>Pada Tanggal</td><td>: {{ $permohonan->tanggal_pindah?->translatedFormat('d F Y') ?? '—' }}</td></tr>
    </table>
    <table class="data-table">
        <tr><td>Alasan Pindah</td><td>: {{ $permohonan->alasan_pindah ?? '—' }}</td></tr>
        <tr><td>Pengikut</td><td>: {{ $permohonan->pengikut ?? '—' }}</td></tr>
    </table>
    <p>Demikian Surat Keterangan ini dibuat atas dasar yang sebenarnya dan dapat dipergunakan sebagaimana mestinya.</p>
</div>
@endsection
