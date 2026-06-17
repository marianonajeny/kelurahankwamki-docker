<?php

namespace App\Http\Requests;

use App\Models\Layanan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermohonanLayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function layananSlug(): ?string
    {
        $layanan = $this->route('layanan');

        return $layanan instanceof Layanan ? $layanan->slug : null;
    }

    /** @return array<string, array<int, mixed>> */
    protected function biodataUmumRules(bool $withStatusPekerjaan = true): array
    {
        $rules = [
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => ['required', Rule::in(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])],
        ];

        if ($withStatusPekerjaan) {
            $rules['status_perkawinan'] = ['required', Rule::in(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'])];
            $rules['pekerjaan'] = ['required', 'string', 'max:100'];
        }

        return $rules;
    }

    public function rules(): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'alamat' => ['required', 'string', 'max:1000'],
            'keperluan' => ['required', 'string', 'max:2000'],
        ];

        $slug = $this->layananSlug();

        $rules = match ($slug) {
            Layanan::SLUG_SKTM,
            Layanan::SLUG_DOMISILI,
            Layanan::SLUG_BELUM_MENIKAH,
            Layanan::SLUG_PINDAH => array_merge($rules, $this->biodataUmumRules()),
            Layanan::SLUG_KELAHIRAN => array_merge($rules, $this->biodataUmumRules(false), [
                'anak_ke' => ['required', 'string', 'max:10'],
                'nama_ayah' => ['required', 'string', 'max:150'],
                'nik_ayah' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
                'nama_ibu' => ['required', 'string', 'max:150'],
                'nik_ibu' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            ]),
            default => $rules,
        };

        if ($slug === Layanan::SLUG_DOMISILI) {
            $rules['tahun_domisili'] = ['required', 'string', 'max:10', 'regex:/^[0-9]{4}$/'];
        }

        if ($slug === Layanan::SLUG_PINDAH) {
            $rules = array_merge($rules, [
                'pendidikan' => ['required', 'string', 'max:100'],
                'kelurahan_tujuan' => ['required', 'string', 'max:100'],
                'kecamatan_tujuan' => ['nullable', 'string', 'max:100'],
                'kota_tujuan' => ['nullable', 'string', 'max:100'],
                'provinsi_tujuan' => ['nullable', 'string', 'max:100'],
                'tanggal_pindah' => ['required', 'date'],
                'alasan_pindah' => ['required', 'string', 'max:2000'],
                'pengikut' => ['nullable', 'string', 'max:2000'],
            ]);
        }

        /** @var Layanan|null $layanan */
        $layanan = $this->route('layanan');
        $berkasItems = $layanan?->persyaratanBerkas() ?? [];

        if (count($berkasItems) > 0) {
            foreach ($berkasItems as $item) {
                $fileRule = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
                array_unshift($fileRule, $item['wajib'] ? 'required' : 'nullable');
                $rules['lampiran_berkas.'.$item['key']] = $fileRule;
            }
        } else {
            $rules['lampiran'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'nik.size' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',
            'nik_ayah.size' => 'NIK ayah harus terdiri dari 16 digit angka.',
            'nik_ibu.size' => 'NIK ibu harus terdiri dari 16 digit angka.',
            'lampiran.mimes' => 'Lampiran harus berformat PDF, JPG, atau PNG.',
            'lampiran.max' => 'Ukuran lampiran maksimal 5 MB.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'tahun_domisili.regex' => 'Tahun domisili harus berformat 4 digit (contoh: 2010).',
        ];

        /** @var Layanan|null $layanan */
        $layanan = $this->route('layanan');

        foreach ($layanan?->persyaratanBerkas() ?? [] as $item) {
            $field = 'lampiran_berkas.'.$item['key'];
            $messages[$field.'.required'] = $item['label'].' wajib diunggah.';
            $messages[$field.'.mimes'] = $item['label'].' harus berformat PDF, JPG, atau PNG.';
            $messages[$field.'.max'] = $item['label'].' maksimal 5 MB.';
        }

        return $messages;
    }
}
