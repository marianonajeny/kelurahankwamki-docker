@props(['layanan'])

@php
    use App\Models\Layanan;
    $berkasItems = $layanan->persyaratanBerkas();
    $inputClass = 'mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest';
    $slug = $layanan->slug;
    $usesBiodata = in_array($slug, [Layanan::SLUG_SKTM, Layanan::SLUG_DOMISILI, Layanan::SLUG_BELUM_MENIKAH, Layanan::SLUG_PINDAH], true);
    $isKelahiran = $slug === Layanan::SLUG_KELAHIRAN;
    $namaLabel = $isKelahiran ? 'Nama Lengkap Anak' : 'Nama Lengkap';
@endphp

<form id="form-permohonan-layanan" action="{{ route('layanan.ajukan', $layanan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div>
        <label for="nama" class="block text-sm font-medium text-gray-700">{{ $namaLabel }} <span class="text-red-600">*</span></label>
        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required maxlength="150" class="{{ $inputClass }} @error('nama') border-red-500 @enderror">
        @error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    @if($usesBiodata)
    <div class="rounded-lg border border-kwamki-forest/20 bg-kwamki-forest/5 p-4 space-y-4">
        <h3 class="text-sm font-semibold text-kwamki-forest">Data diri</h3>
        <x-permohonan-biodata-umum-fields :input-class="$inputClass" />
    </div>
    @endif

    @if($isKelahiran)
    <div class="rounded-lg border border-kwamki-forest/20 bg-kwamki-forest/5 p-4 space-y-4">
        <h3 class="text-sm font-semibold text-kwamki-forest">Data kelahiran</h3>
        <x-permohonan-biodata-umum-fields :input-class="$inputClass" :with-status-pekerjaan="false" />
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="anak_ke" class="block text-sm font-medium text-gray-700">Anak Ke <span class="text-red-600">*</span></label>
                <input type="text" name="anak_ke" id="anak_ke" value="{{ old('anak_ke') }}" required maxlength="10" class="{{ $inputClass }} @error('anak_ke') border-red-500 @enderror">
                @error('anak_ke')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="nama_ayah" class="block text-sm font-medium text-gray-700">Nama Lengkap Ayah <span class="text-red-600">*</span></label>
                <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}" required maxlength="150" class="{{ $inputClass }} @error('nama_ayah') border-red-500 @enderror">
                @error('nama_ayah')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="nik_ayah" class="block text-sm font-medium text-gray-700">NIK Ayah <span class="text-red-600">*</span></label>
                <input type="text" name="nik_ayah" id="nik_ayah" value="{{ old('nik_ayah') }}" required maxlength="16" pattern="[0-9]{16}" class="{{ $inputClass }} @error('nik_ayah') border-red-500 @enderror">
                @error('nik_ayah')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="nama_ibu" class="block text-sm font-medium text-gray-700">Nama Lengkap Ibu <span class="text-red-600">*</span></label>
                <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}" required maxlength="150" class="{{ $inputClass }} @error('nama_ibu') border-red-500 @enderror">
                @error('nama_ibu')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="nik_ibu" class="block text-sm font-medium text-gray-700">NIK Ibu <span class="text-red-600">*</span></label>
                <input type="text" name="nik_ibu" id="nik_ibu" value="{{ old('nik_ibu') }}" required maxlength="16" pattern="[0-9]{16}" class="{{ $inputClass }} @error('nik_ibu') border-red-500 @enderror">
                @error('nik_ibu')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
    @endif

    @if($slug === Layanan::SLUG_DOMISILI)
    <div>
        <label for="tahun_domisili" class="block text-sm font-medium text-gray-700">Berdomisili Sejak Tahun <span class="text-red-600">*</span></label>
        <input type="text" name="tahun_domisili" id="tahun_domisili" value="{{ old('tahun_domisili') }}" required maxlength="4" pattern="[0-9]{4}" placeholder="2010" class="{{ $inputClass }} @error('tahun_domisili') border-red-500 @enderror">
        @error('tahun_domisili')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    @endif

    @if($slug === Layanan::SLUG_PINDAH)
    <div class="rounded-lg border border-kwamki-forest/20 bg-kwamki-forest/5 p-4 space-y-4">
        <h3 class="text-sm font-semibold text-kwamki-forest">Data pindah</h3>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="pendidikan" class="block text-sm font-medium text-gray-700">Pendidikan <span class="text-red-600">*</span></label>
                <input type="text" name="pendidikan" id="pendidikan" value="{{ old('pendidikan') }}" required maxlength="100" class="{{ $inputClass }} @error('pendidikan') border-red-500 @enderror">
                @error('pendidikan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="tanggal_pindah" class="block text-sm font-medium text-gray-700">Tanggal Pindah <span class="text-red-600">*</span></label>
                <input type="date" name="tanggal_pindah" id="tanggal_pindah" value="{{ old('tanggal_pindah') }}" required class="{{ $inputClass }} @error('tanggal_pindah') border-red-500 @enderror">
                @error('tanggal_pindah')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kelurahan_tujuan" class="block text-sm font-medium text-gray-700">Kelurahan Tujuan <span class="text-red-600">*</span></label>
                <input type="text" name="kelurahan_tujuan" id="kelurahan_tujuan" value="{{ old('kelurahan_tujuan') }}" required maxlength="100" class="{{ $inputClass }} @error('kelurahan_tujuan') border-red-500 @enderror">
                @error('kelurahan_tujuan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kecamatan_tujuan" class="block text-sm font-medium text-gray-700">Kecamatan Tujuan</label>
                <input type="text" name="kecamatan_tujuan" id="kecamatan_tujuan" value="{{ old('kecamatan_tujuan', 'Mimika Baru') }}" maxlength="100" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="kota_tujuan" class="block text-sm font-medium text-gray-700">Kota Tujuan</label>
                <input type="text" name="kota_tujuan" id="kota_tujuan" value="{{ old('kota_tujuan', 'Mimika') }}" maxlength="100" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="provinsi_tujuan" class="block text-sm font-medium text-gray-700">Provinsi Tujuan</label>
                <input type="text" name="provinsi_tujuan" id="provinsi_tujuan" value="{{ old('provinsi_tujuan', 'Papua Tengah') }}" maxlength="100" class="{{ $inputClass }}">
            </div>
            <div class="sm:col-span-2">
                <label for="alasan_pindah" class="block text-sm font-medium text-gray-700">Alasan Pindah <span class="text-red-600">*</span></label>
                <textarea name="alasan_pindah" id="alasan_pindah" rows="2" required maxlength="2000" class="{{ $inputClass }}">{{ old('alasan_pindah') }}</textarea>
                @error('alasan_pindah')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="pengikut" class="block text-sm font-medium text-gray-700">Pengikut (opsional)</label>
                <textarea name="pengikut" id="pengikut" rows="2" maxlength="2000" class="{{ $inputClass }}">{{ old('pengikut') }}</textarea>
            </div>
        </div>
    </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="nik" class="block text-sm font-medium text-gray-700">NIK <span class="text-red-600">*</span></label>
            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required maxlength="16" pattern="[0-9]{16}" class="{{ $inputClass }} @error('nik') border-red-500 @enderror">
            @error('nik')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP <span class="text-red-600">*</span></label>
            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required maxlength="20" class="{{ $inputClass }} @error('no_hp') border-red-500 @enderror">
            @error('no_hp')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email (opsional)</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" maxlength="120" class="{{ $inputClass }}">
    </div>
    <div>
        <label for="alamat" class="block text-sm font-medium text-gray-700">{{ $slug === Layanan::SLUG_PINDAH ? 'Alamat Asal' : 'Alamat' }} <span class="text-red-600">*</span></label>
        <textarea name="alamat" id="alamat" rows="3" required maxlength="1000" class="{{ $inputClass }}">{{ old('alamat') }}</textarea>
        @error('alamat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan <span class="text-red-600">*</span></label>
        <textarea name="keperluan" id="keperluan" rows="3" required maxlength="2000" class="{{ $inputClass }}">{{ old('keperluan') }}</textarea>
        @error('keperluan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
        <h3 class="text-sm font-semibold text-kwamki-forest">Berkas persyaratan</h3>
        <p class="mt-1 text-xs text-gray-500">Format PDF, JPG, atau PNG. Maks. 5 MB per file.</p>
        <div class="mt-4 space-y-4">
            @forelse($berkasItems as $item)
                <div>
                    <label for="lampiran_berkas_{{ $item['key'] }}" class="block text-sm font-medium text-gray-700">
                        {{ $item['label'] }} @if($item['wajib'])<span class="text-red-600">*</span>@else<span class="text-gray-500">(opsional)</span>@endif
                    </label>
                    <input type="file" name="lampiran_berkas[{{ $item['key'] }}]" id="lampiran_berkas_{{ $item['key'] }}"
                           accept=".pdf,.jpg,.jpeg,.png" @if($item['wajib']) required @endif class="{{ $inputClass }} @error('lampiran_berkas.'.$item['key']) border-red-500 @enderror">
                    @error('lampiran_berkas.'.$item['key'])<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            @empty
                <div>
                    <label for="lampiran" class="block text-sm font-medium text-gray-700">Lampiran (opsional)</label>
                    <input type="file" name="lampiran" id="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="{{ $inputClass }}">
                </div>
            @endforelse
        </div>
    </div>
    <button type="submit" id="btn-submit-permohonan" class="btn-primary w-full sm:w-auto">Kirim Permohonan</button>
</form>
