@extends('layouts.admin')
@php $pageTitle = 'Tambah Pengumuman'; $breadcrumb = ['Pengumuman' => route('admin.pengumuman.index'), 'Tambah' => null]; @endphp
@section('title', 'Tambah Pengumuman')

@section('content')
<form action="{{ route('admin.pengumuman.store') }}" method="POST" class="max-w-2xl rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @include('admin.pengumuman._form')
    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">Simpan</button>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
