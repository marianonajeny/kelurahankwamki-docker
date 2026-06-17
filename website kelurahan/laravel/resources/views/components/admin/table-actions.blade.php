@props(['showRoute' => null, 'editRoute', 'destroyRoute', 'itemName' => 'item'])

<div class="inline-flex flex-col items-end gap-1.5">
    <div class="flex items-center gap-2">
        <a href="{{ $editRoute }}" class="rounded bg-kwamki-sand px-2.5 py-1 text-xs font-medium text-kwamki-forest hover:bg-kwamki-gold/30">Edit</a>
        <form action="{{ $destroyRoute }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus {{ $itemName }} ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-100">Hapus</button>
        </form>
    </div>
    @if($showRoute)
        <a href="{{ $showRoute }}" class="rounded bg-kwamki-sand px-2.5 py-1 text-xs font-medium text-kwamki-forest hover:bg-kwamki-gold/30">Detail</a>
    @endif
</div>
