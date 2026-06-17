@props(['inputName' => 'ttd_signature', 'canvasId' => 'ttd-signature-canvas'])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4']) }}>
    <p class="text-sm font-medium text-gray-700">Gambar tanda tangan di kanvas</p>
    <p class="mt-1 text-xs text-gray-500">Gunakan mouse atau sentuhan jari di area di bawah.</p>

    <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 bg-white">
        <canvas id="{{ $canvasId }}" width="480" height="160" class="block w-full max-w-full touch-none" style="touch-action: none;"></canvas>
    </div>

    <div class="mt-3 flex flex-wrap gap-2">
        <button type="button" data-signature-clear="{{ $canvasId }}"
                class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Hapus
        </button>
        <p class="self-center text-xs text-gray-500" data-signature-status="{{ $canvasId }}">Belum ada tanda tangan</p>
    </div>

    <input type="hidden" name="{{ $inputName }}" id="{{ $canvasId }}-input" value="{{ old($inputName) }}">
    @error($inputName)
        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
