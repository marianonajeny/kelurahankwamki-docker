@extends('layouts.admin')
@php $pageTitle = 'Tambah Layanan'; $breadcrumb = ['Layanan' => route('admin.layanan.index'), 'Tambah' => null]; @endphp
@section('title', 'Tambah Layanan')

@section('content')
<form action="{{ route('admin.layanan.store') }}" method="POST" class="max-w-2xl rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @include('admin.layanan._form')
    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">Simpan</button>
        <a href="{{ route('admin.layanan.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
