<?php

namespace App\Http\Requests\Admin;

use App\Models\PermohonanLayanan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermohonanLayananUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'catatan_admin' => $this->input('catatan_admin') ?: null,
        ]);
    }

    public function rules(): array
    {
        $status = PermohonanLayanan::normalizeStatus((string) $this->input('status'));

        return [
            'status' => ['required', Rule::in(PermohonanLayanan::statuses())],
            'catatan_admin' => [
                Rule::requiredIf(in_array($status, [
                    PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN,
                    PermohonanLayanan::STATUS_DITOLAK,
                ], true)),
                'nullable',
                'string',
                'max:5000',
            ],
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'alamat' => ['required', 'string', 'max:1000'],
            'keperluan' => ['required', 'string', 'max:2000'],
            'check_nik_nama' => [Rule::requiredIf($status === PermohonanLayanan::STATUS_TERVERIFIKASI_ADMIN), 'accepted'],
            'check_alamat_keperluan' => [Rule::requiredIf($status === PermohonanLayanan::STATUS_TERVERIFIKASI_ADMIN), 'accepted'],
            'check_lampiran' => [Rule::requiredIf($status === PermohonanLayanan::STATUS_TERVERIFIKASI_ADMIN), 'accepted'],
        ];
    }
}
