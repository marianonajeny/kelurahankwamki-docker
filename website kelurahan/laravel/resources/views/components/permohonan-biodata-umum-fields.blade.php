@props(['inputClass' => 'mt-1 w-full rounded-lg border border-gray-300 px-4 py-2', 'withStatusPekerjaan' => true])

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir <span class="text-red-600">*</span></label>
        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" required maxlength="100" class="{{ $inputClass }} @error('tempat_lahir') border-red-500 @enderror">
        @error('tempat_lahir')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-600">*</span></label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required max="{{ date('Y-m-d') }}" class="{{ $inputClass }} @error('tanggal_lahir') border-red-500 @enderror">
        @error('tanggal_lahir')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-600">*</span></label>
        <select name="jenis_kelamin" id="jenis_kelamin" required class="{{ $inputClass }} @error('jenis_kelamin') border-red-500 @enderror">
            <option value="">— Pilih —</option>
            @foreach(['Laki-laki', 'Perempuan'] as $jk)
                <option value="{{ $jk }}" @selected(old('jenis_kelamin') === $jk)>{{ $jk }}</option>
            @endforeach
        </select>
        @error('jenis_kelamin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="agama" class="block text-sm font-medium text-gray-700">Agama <span class="text-red-600">*</span></label>
        <select name="agama" id="agama" required class="{{ $inputClass }} @error('agama') border-red-500 @enderror">
            <option value="">— Pilih —</option>
            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $ag)
                <option value="{{ $ag }}" @selected(old('agama') === $ag)>{{ $ag }}</option>
            @endforeach
        </select>
        @error('agama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    @if($withStatusPekerjaan)
    <div>
        <label for="status_perkawinan" class="block text-sm font-medium text-gray-700">Status Perkawinan <span class="text-red-600">*</span></label>
        <select name="status_perkawinan" id="status_perkawinan" required class="{{ $inputClass }} @error('status_perkawinan') border-red-500 @enderror">
            <option value="">— Pilih —</option>
            @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $sp)
                <option value="{{ $sp }}" @selected(old('status_perkawinan') === $sp)>{{ $sp }}</option>
            @endforeach
        </select>
        @error('status_perkawinan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan <span class="text-red-600">*</span></label>
        <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" required maxlength="100" class="{{ $inputClass }} @error('pekerjaan') border-red-500 @enderror">
        @error('pekerjaan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    @endif
</div>
