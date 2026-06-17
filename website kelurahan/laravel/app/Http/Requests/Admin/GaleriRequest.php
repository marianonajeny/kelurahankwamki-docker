<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GaleriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'judul' => ['required', 'string', 'max:255'],
            'gambar' => [$isUpdate ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
            'kategori' => ['required', 'string', 'max:50'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'gambar.mimes' => 'Format gambar harus JPG, PNG, GIF, atau WEBP (bukan HEIC).',
            'gambar.max' => 'Ukuran gambar maksimal 10 MB.',
        ];
    }
}
