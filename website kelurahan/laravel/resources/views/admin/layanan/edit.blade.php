@extends('layouts.admin')
@php $pageTitle = 'Edit Layanan'; $breadcrumb = ['Layanan' => route('admin.layanan.index'), 'Edit' => null]; @endphp
@section('title', 'Edit Layanan')

@section('content')
<form action="{{ route('admin.layanan.update', $layanan) }}" method="POST" class="max-w-2xl rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.layanan._form', ['layanan' => $layanan])
    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">Perbarui</button>
        <a href="{{ route('admin.layanan.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
