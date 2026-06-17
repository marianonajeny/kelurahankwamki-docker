<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->input('slug') ?: null,
            'dokumen_url' => $this->input('dokumen_url') ?: null,
            'link_url' => $this->input('link_url') ?: null,
            'petugas' => $this->input('petugas') ?: null,
            'lokasi' => $this->input('lokasi') ?: null,
            'persyaratan' => $this->input('persyaratan') ?: null,
            'alur' => $this->input('alur') ?: null,
        ]);
    }

    public function rules(): array
    {
        $layanan = $this->route('layanan');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('layanans', 'slug')->ignore(optional($layanan)->id),
            ],
            'deskripsi' => ['nullable', 'string'],
            'kategori' => ['required', 'string', 'max:50'],
            'persyaratan' => ['nullable', 'string', 'max:10000'],
            'alur' => ['nullable', 'string', 'max:10000'],
            'estimasi_waktu' => ['required', 'string', 'max:120'],
            'biaya' => ['required', 'string', 'max:120'],
            'petugas' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'dokumen_url' => ['nullable', 'url', 'max:500'],
            'menerima_permohonan_online' => ['boolean'],
            'ikon' => ['nullable', 'string', 'max:50'],
            'link_url' => ['nullable', 'url', 'max:500'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Slug sudah dipakai layanan lain; ubah slug manual.',
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung (tanpa spasi atau garis miring).',
        ];
    }
}
