<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

#[Fillable(['nama', 'email', 'telepon', 'subjek', 'pesan', 'is_read'])]
class PesanKontak extends Model
{
    protected $table = 'pesan_kontaks';

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public static function tableReady(): bool
    {
        return Schema::hasTable((new static)->getTable());
    }

    public static function unreadCount(): int
    {
        if (! static::tableReady()) {
            return 0;
        }

        return static::where('is_read', false)->count();
    }

    public static function latestUnread(int $limit = 5): Collection
    {
        if (! static::tableReady()) {
            return collect();
        }

        return static::where('is_read', false)->latest()->limit($limit)->get();
    }
}
