<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'judul', 'konten', 'urutan'])]
class ProfilSection extends Model
{
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan');
    }
}
