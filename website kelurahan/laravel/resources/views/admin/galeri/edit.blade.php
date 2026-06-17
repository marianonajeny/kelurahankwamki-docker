@extends('layouts.admin')
@php $pageTitle = 'Edit Galeri'; $breadcrumb = ['Galeri' => route('admin.galeri.index'), 'Edit' => null]; @endphp
@section('title', 'Edit Galeri')

@section('content')
<form action="{{ route('admin.galeri.update', $galeri) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.galeri._form', ['galeri' => $galeri])
    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">Perbarui</button>
        <a href="{{ route('admin.galeri.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
