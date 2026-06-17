@extends('layouts.admin')
@php $pageTitle = 'Tambah Berita'; $breadcrumb = ['Berita' => route('admin.berita.index'), 'Tambah' => null]; @endphp
@section('title', 'Tambah Berita')

@section('content')
<form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl rounded-xl bg-white p-6 shadow-sm" id="berita-form">
    @csrf
    @include('admin.berita._form')
    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary" id="btn-simpan">Simpan</button>
        <a href="{{ route('admin.berita.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('berita-form')?.addEventListener('submit', function () {
    const btn = document.getElementById('btn-simpan');
    if (btn) {
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';
    }
});
</script>
@endpush
@endsection
