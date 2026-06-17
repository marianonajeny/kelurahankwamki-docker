@extends('layouts.admin')
@php $pageTitle = 'Tambah Galeri'; $breadcrumb = ['Galeri' => route('admin.galeri.index'), 'Tambah' => null]; @endphp
@section('title', 'Tambah Galeri')

@section('content')
<form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @include('admin.galeri._form')
    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">Simpan</button>
        <a href="{{ route('admin.galeri.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
