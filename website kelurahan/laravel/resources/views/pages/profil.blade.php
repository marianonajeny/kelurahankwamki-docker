@extends('layouts.app')

@section('title', 'Profil Kelurahan')
@section('meta_description', 'Profil Kelurahan Kwamki — sejarah, visi misi, dan struktur organisasi.')

@php $breadcrumb = ['Profil' => null]; @endphp

@section('content')
    <x-page-header title="Profil Kelurahan Kwamki" subtitle="Distrik Mimika Baru, Kabupaten Mimika, Papua Tengah" />

    <x-page-content-section variant="warm" maxWidth="max-w-4xl">
        <div class="reveal-stagger space-y-12">
            @foreach($sections as $section)
            <article id="{{ $section->key }}" class="reveal card card-accent p-8">
                <h2 class="section-title section-title-accent text-xl">{{ $section->judul }}</h2>
                <div class="mt-4 prose prose-sm max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($section->konten)) !!}
                </div>
            </article>
            @endforeach
        </div>
    </x-page-content-section>
@endsection
