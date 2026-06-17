@extends('layouts.admin')

@php
    $pageTitle = $isLurah ? 'Verifikasi surat' : 'Detail permohonan';
    $breadcrumb = ['Permohonan' => route('admin.permohonan.index'), $permohonan->nomor => null];
    $user = auth()->user();
    $statusPermohonan = \App\Models\PermohonanLayanan::normalizeStatus($permohonan->status);
    $lurahMenungguVerifikasi = $statusPermohonan === \App\Models\PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN;
    $lurahBisaTtd = $isLurah && $lurahMenungguVerifikasi && $permohonan->hasSuratUntukLurah();
    $pengaturanTtd = \App\Models\Pengaturan::ttdLurah();
    $ttdGlobalBelumLengkap = $isLurah && blank($pengaturanTtd['nama']);
    $statusRevisiDariLurah = $statusPermohonan === \App\Models\PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN;
@endphp

@section('title', $permohonan->nomor)

@section('content')
<div class="mx-auto max-w-4xl space-y-8">
    @if(! $isLurah && $user?->isAdmin() && $statusRevisiDariLurah)
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-900">
            <p class="font-semibold">Permintaan revisi dari Kepala Kelurahan</p>
            @if($permohonan->catatan_admin)
                <p class="mt-2 whitespace-pre-line rounded-lg border border-rose-100 bg-white px-4 py-3 text-rose-950">{{ $permohonan->catatan_admin }}</p>
            @else
                <p class="mt-2 text-rose-800">Kepala Kelurahan meminta perbaikan surat. Silakan periksa dan sesuaikan isi surat.</p>
            @endif
            <p class="mt-3 text-rose-800">Langkah kerja: <strong>Susun Surat</strong> → perbaiki isi surat → <strong>Terbitkan ulang</strong> → <strong>Kirim ke Kepala Kelurahan</strong>.</p>
            <a href="{{ route('admin.permohonan.susun-surat', $permohonan) }}"
               class="mt-4 inline-flex rounded-lg bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-800">
                Buka Susun Surat
            </a>
        </div>
    @endif

    @if(! $isLurah && $user?->isAdmin() && $permohonan->perluKirimKeAntrianLurah())
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            <p class="font-semibold">PDF sudah ada tetapi belum masuk antrian Kepala Kelurahan.</p>
            <p class="mt-1">Kirim permohonan agar muncul di halaman verifikasi kepala kelurahan.</p>
            <form method="POST" action="{{ route('admin.permohonan.kirim-ke-kepala-kelurahan', $permohonan) }}" class="mt-3"
                  onsubmit="return confirm('Kirim ke antrian Kepala Kelurahan?')">
                @csrf
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Kirim ke Kepala Kelurahan
                </button>
            </form>
        </div>
    @endif

    <div class="flex flex-wrap items-start justify-between gap-4 rounded-xl bg-white p-6 shadow-sm">
        <div>
            <p class="text-xs uppercase text-gray-500">Nomor permohonan</p>
            <p class="font-mono text-xl font-bold text-kwamki-forest">{{ $permohonan->nomor }}</p>
            <p class="mt-2 text-sm text-gray-600">{{ $permohonan->layanan->nama }}</p>
        </div>
        <div class="flex flex-col items-end gap-3">
            <x-status-badge :status="$permohonan->status" />
            <p class="text-xs text-gray-500">{{ $permohonan->created_at->translatedFormat('d F Y H:i') }}</p>
            @if(! $isLurah && $user?->isAdmin() && \App\Models\PermohonanLayanan::normalizeStatus($permohonan->status) === \App\Models\PermohonanLayanan::STATUS_DIPROSES_ADMIN)
                <form method="POST" action="{{ route('admin.permohonan.proses-lanjut-surat', $permohonan) }}"
                      onsubmit="return confirm('Lanjutkan proses pembuatan surat keterangan untuk permohonan {{ $permohonan->nomor }}?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="rounded-lg border border-kwamki-ocean px-3 py-1.5 text-xs font-semibold text-kwamki-ocean hover:bg-kwamki-ocean hover:text-white">
                        Proses Lanjut Pembuatan Surat Keterangan
                    </button>
                </form>
            @endif
            @if($permohonan->canAdvance($user))
                <x-admin.permohonan-actions :permohonan="$permohonan" redirect="show" :show-detail="false" />
            @endif
        </div>
    </div>

    <div class="rounded-xl border border-kwamki-forest/10 bg-white p-6 shadow-sm">
        <h3 class="font-bold text-kwamki-forest">{{ $isLurah ? 'Surat untuk diverifikasi' : 'Surat permohonan' }}</h3>
        @if($isLurah)
            <p class="mt-1 text-sm text-gray-600">Periksa surat yang dikirim admin. Verifikasi dan tanda tangan tanpa mengubah isi surat.</p>
        @else
            <p class="mt-1 text-sm text-gray-600">Susun draft surat sesuai jenis layanan, lalu terbitkan sebagai PDF.</p>
        @endif

        @if($isLurah)
            @if($suratIframeSrc ?? null)
                <div class="mt-4">
                    <p class="text-xs font-semibold uppercase text-gray-500">Dokumen surat dari admin</p>
                    <iframe src="{{ $suratIframeSrc }}" title="Surat {{ $permohonan->nomor }}"
                            class="mt-2 h-[min(70vh,640px)] w-full rounded-lg border border-gray-200 bg-gray-50"></iframe>
                </div>
            @else
                <p class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    Surat belum disusun admin. Minta admin menyelesaikan <strong>Susun Surat</strong> dan menerbitkan PDF.
                </p>
            @endif
        @endif

        @if($permohonan->nomor_surat || $permohonan->tanggal_surat)
        <dl class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-gray-500">Nomor surat</dt>
                <dd class="font-medium">{{ $permohonan->nomor_surat ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Tanggal surat</dt>
                <dd class="font-medium">{{ $permohonan->tanggal_surat?->translatedFormat('d F Y') ?? '—' }}</dd>
            </div>
            @if($permohonan->surat_diterbitkan_at)
            <div class="sm:col-span-2">
                <dt class="text-gray-500">Diterbitkan</dt>
                <dd class="font-medium">{{ $permohonan->surat_diterbitkan_at->translatedFormat('d F Y H:i') }}</dd>
            </div>
            @endif
        </dl>
        @endif

        @php
            $punyaNomorTanggalSurat = filled($permohonan->nomor_surat) && filled($permohonan->tanggal_surat);
            $previewTtdParams = array_filter([
                'nomor_surat' => $permohonan->nomor_surat,
                'tanggal_surat' => $permohonan->tanggal_surat?->format('Y-m-d'),
                'with_ttd' => '1',
            ]);
            $previewTtdUrl = route('admin.permohonan.surat.preview', $permohonan)
                . (count($previewTtdParams) ? '?'.http_build_query($previewTtdParams) : '?with_ttd=1');
        @endphp

        <div class="mt-4 flex flex-wrap gap-2">
            @if(! $isLurah)
                <a href="{{ route('admin.permohonan.susun-surat', $permohonan) }}"
                   class="inline-flex items-center rounded-lg bg-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light">
                    Susun Surat
                </a>

                @if($permohonan->hasSuratDraft())
                <form method="POST" action="{{ route('admin.permohonan.terbitkan-surat', $permohonan) }}" class="inline"
                      onsubmit="return confirm('Terbitkan surat PDF untuk permohonan {{ $permohonan->nomor }}?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-lg bg-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light">
                        Terbitkan Surat
                    </button>
                </form>
                @else
                <a href="{{ route('admin.permohonan.susun-surat', $permohonan) }}"
                   class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100">
                    Terbitkan Surat
                </a>
                @endif

                @if($permohonan->canKirimKeKepalaKelurahan($user))
                <form method="POST" action="{{ route('admin.permohonan.kirim-ke-kepala-kelurahan', $permohonan) }}" class="inline"
                      onsubmit="return confirm('Kirim permohonan {{ $permohonan->nomor }} ke Kepala Kelurahan untuk verifikasi dan tanda tangan?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Kirim ke Kepala Kelurahan
                    </button>
                </form>
                @elseif(! $permohonan->hasSuratTerbit() && ! in_array(\App\Models\PermohonanLayanan::normalizeStatus($permohonan->status), ['menunggu_verifikasi_kepala_kelurahan', 'ditandatangani_kepala_kelurahan', 'selesai', 'ditolak'], true))
                <p class="w-full text-xs text-gray-500">Terbitkan surat PDF terlebih dahulu untuk mengirim ke Kepala Kelurahan.</p>
                @endif
            @endif

            @if($permohonan->hasSuratDraft() || $permohonan->hasSuratTerbit() || ($isLurah && $punyaNomorTanggalSurat))
                <a href="{{ $previewTtdUrl }}" target="_blank" rel="noopener"
                   class="inline-flex items-center rounded-lg bg-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light">
                    Pratinjau TTD
                </a>
            @endif

            @if($permohonan->hasSuratTerbit())
                <a href="{{ route('admin.permohonan.surat.unduh', $permohonan) }}?v={{ $permohonan->updated_at?->timestamp ?? time() }}"
                   class="inline-flex items-center rounded-lg bg-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light">
                    Unduh PDF
                </a>
            @elseif($isLurah && ! ($suratIframeSrc ?? null))
                <p class="w-full text-xs text-amber-700">Surat belum tersedia. Hubungi admin kelurahan.</p>
            @endif
        </div>

        @if($isLurah && $lurahMenungguVerifikasi)
            <div class="mt-6 space-y-4 border-t border-gray-100 pt-6">
                @if($ttdGlobalBelumLengkap)
                    <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                        <p class="font-semibold">Pengaturan TTD global belum lengkap.</p>
                        <p class="mt-1">Lengkapi data penandatangan di Pengaturan TTD terlebih dahulu sebelum melakukan verifikasi.</p>
                        <a href="{{ route('admin.permohonan.pengaturan-ttd.edit') }}"
                           class="mt-2 inline-flex rounded-lg bg-kwamki-forest px-3 py-1.5 text-xs font-semibold text-white hover:bg-kwamki-forest-dark">
                            Atur TTD
                        </a>
                    </div>
                @endif

                <h4 class="text-sm font-semibold text-kwamki-forest">Tindakan Kepala Kelurahan</h4>

                @if($lurahBisaTtd)
                <form method="POST" action="{{ route('admin.permohonan.lanjutkan', $permohonan) }}" class="space-y-4" id="form-verifikasi-ttd"
                      onsubmit="return confirm('Verifikasi dan terapkan tanda tangan pada surat {{ $permohonan->nomor }}?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="redirect" value="show">

                    @error('ttd')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if($permohonan->canAdvance($user) && ! $ttdGlobalBelumLengkap)
                        <button type="submit"
                                class="rounded-lg bg-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light">
                            Verifikasi &amp; TTD
                        </button>
                    @elseif(! $permohonan->canAdvance($user))
                        <p class="text-sm text-amber-800">Permohonan tidak dapat diverifikasi pada langkah ini. Hubungi admin kelurahan.</p>
                    @endif
                </form>
                @else
                <p class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    Surat belum siap ditandatangani. Minta admin menyelesaikan <strong>Susun Surat</strong> dan menerbitkan PDF terlebih dahulu.
                </p>
                @endif

                <form method="POST" action="{{ route('admin.permohonan.minta-revisi', $permohonan) }}" class="space-y-3 rounded-lg border border-rose-100 bg-rose-50/50 p-4">
                    @csrf
                    <label for="catatan_revisi" class="block text-sm font-medium text-rose-900">Minta revisi ke admin</label>
                    <textarea name="catatan_revisi" id="catatan_revisi" rows="3" required maxlength="2000"
                              placeholder="Jelaskan bagian surat yang perlu diperbaiki..."
                              class="w-full rounded-lg border border-rose-200 px-3 py-2 text-sm">{{ old('catatan_revisi') }}</textarea>
                    @error('catatan_revisi')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="rounded-lg border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-800 hover:bg-rose-50"
                            onclick="return confirm('Kirim permintaan revisi ke admin kelurahan?')">
                        Minta Revisi
                    </button>
                </form>
            </div>
        @elseif($isLurah)
            <div class="mt-6 space-y-4 border-t border-gray-100 pt-6">
                @if(! $lurahMenungguVerifikasi)
                    <p class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                        Tanda tangan digital hanya dapat dibuat saat status <strong>Menunggu Verifikasi Kepala Kelurahan</strong>.
                        Status saat ini: <x-status-badge :status="$permohonan->status" />.
                    </p>
                @endif
                @if($permohonan->canAdvance($user))
                    <x-admin.permohonan-actions :permohonan="$permohonan" redirect="show" :show-detail="false" />
                @endif
            </div>
        @endif
    </div>

    @if(! $isLurah && $user?->isAdmin() && $statusPermohonan === \App\Models\PermohonanLayanan::STATUS_DIAJUKAN)
        <div class="rounded-xl border border-kwamki-forest/20 bg-white p-6 shadow-sm">
            <h3 class="font-bold text-kwamki-forest">Pemeriksaan data awal</h3>
            <p class="mt-1 text-sm text-gray-600">Periksa kelengkapan data pemohon sebelum menerima atau menolak permohonan.</p>
            <div class="mt-4 flex flex-wrap gap-3">
                <form method="POST" action="{{ route('admin.permohonan.terima', $permohonan) }}" class="inline"
                      onsubmit="return confirm('Terima permohonan {{ $permohonan->nomor }} dan lanjutkan proses?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        Terima Permohonan
                    </button>
                </form>
                <button type="button"
                        onclick="document.getElementById('dialog-tolak-{{ $permohonan->id }}').showModal()"
                        class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">
                    Tolak
                </button>
            </div>

            <dialog id="dialog-tolak-{{ $permohonan->id }}" class="w-full max-w-lg rounded-xl border border-gray-200 p-0 shadow-xl backdrop:bg-black/40 open:mx-auto open:my-auto">
                <form method="POST" action="{{ route('admin.permohonan.tolak', $permohonan) }}" class="p-6">
                    @csrf
                    <h4 class="text-lg font-bold text-kwamki-forest">Tolak permohonan</h4>
                    <p class="mt-1 text-sm text-gray-600">Pesan berikut akan dikirim ke WhatsApp pemohon. Data permohonan akan dihapus setelah pengiriman berhasil.</p>
                    <label for="pesan_penolakan_{{ $permohonan->id }}" class="mt-4 block text-sm font-medium text-gray-700">Pesan penolakan</label>
                    <textarea name="pesan_penolakan" id="pesan_penolakan_{{ $permohonan->id }}" rows="6" required maxlength="2000"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('pesan_penolakan', $penolakanTemplate ?? '') }}</textarea>
                    @error('pesan_penolakan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('whatsapp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-6 flex flex-wrap justify-end gap-2">
                        <button type="button" onclick="this.closest('dialog').close()"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                                onclick="return confirm('Kirim WhatsApp dan hapus data permohonan {{ $permohonan->nomor }}?')">
                            Kirim & Hapus
                        </button>
                    </div>
                </form>
            </dialog>
        </div>
        @if($errors->has('whatsapp') || $errors->has('pesan_penolakan'))
            <script>
                document.getElementById('dialog-tolak-{{ $permohonan->id }}')?.showModal();
            </script>
        @endif
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <h3 class="font-bold text-kwamki-forest">Data pemohon</h3>
        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
            <div><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $permohonan->nama }}</dd></div>
            <div><dt class="text-gray-500">NIK</dt><dd class="font-mono">{{ $permohonan->nik }}</dd></div>
            <div><dt class="text-gray-500">No. HP</dt><dd>{{ $permohonan->no_hp }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd>{{ $permohonan->email ?: '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-gray-500">Alamat</dt><dd class="whitespace-pre-line">{{ $permohonan->alamat }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-gray-500">Keperluan</dt><dd class="whitespace-pre-line">{{ $permohonan->keperluan }}</dd></div>
            @if($permohonan->usesBiodataUmum() || $permohonan->layanan?->slug === \App\Models\Layanan::SLUG_KELAHIRAN)
                <div><dt class="text-gray-500">Tempat lahir</dt><dd>{{ $permohonan->tempat_lahir ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Tanggal lahir</dt><dd>{{ $permohonan->tanggal_lahir?->translatedFormat('d F Y') ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Jenis kelamin</dt><dd>{{ $permohonan->jenis_kelamin ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Agama</dt><dd>{{ $permohonan->agama ?: '—' }}</dd></div>
            @endif
            @if($permohonan->usesBiodataUmum())
                <div><dt class="text-gray-500">Status perkawinan</dt><dd>{{ $permohonan->status_perkawinan ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Pekerjaan</dt><dd>{{ $permohonan->pekerjaan ?: '—' }}</dd></div>
            @endif
            @if($permohonan->layanan?->slug === \App\Models\Layanan::SLUG_DOMISILI)
                <div><dt class="text-gray-500">Berdomisili sejak</dt><dd>{{ $permohonan->tahun_domisili ?: '—' }}</dd></div>
            @endif
            @if($permohonan->layanan?->slug === \App\Models\Layanan::SLUG_KELAHIRAN)
                <div><dt class="text-gray-500">Anak ke</dt><dd>{{ $permohonan->anak_ke ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Nama ayah</dt><dd>{{ $permohonan->nama_ayah ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">NIK ayah</dt><dd class="font-mono">{{ $permohonan->nik_ayah ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Nama ibu</dt><dd>{{ $permohonan->nama_ibu ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">NIK ibu</dt><dd class="font-mono">{{ $permohonan->nik_ibu ?: '—' }}</dd></div>
            @endif
            @if($permohonan->layanan?->slug === \App\Models\Layanan::SLUG_PINDAH)
                <div><dt class="text-gray-500">Pendidikan</dt><dd>{{ $permohonan->pendidikan ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Kelurahan tujuan</dt><dd>{{ $permohonan->kelurahan_tujuan ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Kecamatan tujuan</dt><dd>{{ $permohonan->kecamatan_tujuan ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Kota tujuan</dt><dd>{{ $permohonan->kota_tujuan ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Provinsi tujuan</dt><dd>{{ $permohonan->provinsi_tujuan ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Tanggal pindah</dt><dd>{{ $permohonan->tanggal_pindah?->translatedFormat('d F Y') ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Alasan pindah</dt><dd class="whitespace-pre-line">{{ $permohonan->alasan_pindah ?: '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Pengikut</dt><dd class="whitespace-pre-line">{{ $permohonan->pengikut ?: '—' }}</dd></div>
            @endif
        </dl>

        <div class="mt-6 space-y-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <h4 class="text-sm font-semibold text-gray-800">File persyaratan yang dikirim pemohon</h4>
            @if($permohonan->hasLampiranBerkas())
                <ul class="space-y-4">
                    @foreach($permohonan->lampiran_berkas as $berkas)
                        @php
                            $filePath = $berkas['path'] ?? null;
                            $fileLabel = $berkas['label'] ?? 'Berkas pendukung';
                            $fileName = $berkas['nama_asli'] ?? ($filePath ? basename($filePath) : null);
                            $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : '';
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
                            $isPdf = $extension === 'pdf';
                            $fileUrl = $filePath ? asset('storage/'.$filePath) : null;
                        @endphp
                        @if($filePath)
                            <li class="space-y-3 rounded-md border border-gray-200 bg-white p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $fileLabel }}</p>
                                    <p class="text-xs text-gray-500">{{ $fileName }}</p>
                                </div>
                                @if($isImage)
                                    <img src="{{ $fileUrl }}" alt="{{ $fileLabel }}" class="max-h-[420px] w-full rounded-md border border-gray-200 object-contain bg-gray-100">
                                @elseif($isPdf)
                                    <iframe src="{{ $fileUrl }}" title="{{ $fileLabel }}" class="h-[520px] w-full rounded-md border border-gray-200"></iframe>
                                @endif
                                <div class="flex gap-3 text-xs">
                                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="font-semibold text-kwamki-ocean hover:text-kwamki-gold">Lihat</a>
                                    <a href="{{ $fileUrl }}" download class="font-semibold text-kwamki-ocean hover:text-kwamki-gold">Unduh</a>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @elseif($permohonan->lampiran)
                @php
                    $legacyUrl = asset('storage/'.$permohonan->lampiran);
                    $legacyExt = strtolower(pathinfo($permohonan->lampiran, PATHINFO_EXTENSION));
                @endphp
                <div class="space-y-3 rounded-md border border-gray-200 bg-white p-3">
                    <p class="text-sm font-medium text-gray-800">Lampiran lama</p>
                    @if(in_array($legacyExt, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true))
                        <img src="{{ $legacyUrl }}" alt="Lampiran" class="max-h-[420px] w-full rounded-md border object-contain">
                    @elseif($legacyExt === 'pdf')
                        <iframe src="{{ $legacyUrl }}" class="h-[520px] w-full rounded-md border"></iframe>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-500">Pemohon belum mengirim file persyaratan.</p>
            @endif
        </div>
    </div>

    @if($permohonan->catatan_admin && ! $statusRevisiDariLurah)
        <div class="rounded-xl border border-amber-100 bg-amber-50 p-6 text-sm text-amber-900">
            <strong>Catatan (tercatat)</strong>
            <p class="mt-2 whitespace-pre-line">{{ $permohonan->catatan_admin }}</p>
        </div>
    @endif
</div>
@endsection
