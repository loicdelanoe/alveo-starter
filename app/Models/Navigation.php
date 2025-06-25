<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Navigation extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'navigable_id',
        'navigable_type',
        'target',
        'order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

    public function children(): HasMany {
        return $this->hasMany(Navigation::class, 'parent_id')->orderBy('order');
    }

    public function navigable()
    {
        return $this->morphTo();
    }
}
