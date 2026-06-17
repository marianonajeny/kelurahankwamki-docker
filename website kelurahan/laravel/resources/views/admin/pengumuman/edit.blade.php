@extends('layouts.admin')
@php $pageTitle = 'Edit Pengumuman'; $breadcrumb = ['Pengumuman' => route('admin.pengumuman.index'), 'Edit' => null]; @endphp
@section('title', 'Edit Pengumuman')

@section('content')
<form action="{{ route('admin.pengumuman.update', $pengumuman) }}" method="POST" class="max-w-2xl rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.pengumuman._form', ['pengumuman' => $pengumuman])
    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">Perbarui</button>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
