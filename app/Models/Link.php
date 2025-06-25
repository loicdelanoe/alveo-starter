<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Link extends Model
{
    /** @use HasFactory<\Database\Factories\LinkFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'target',
    ];

    public function navigation(): MorphOne
    {
        return $this->morphOne(Navigation::class, 'navigable');
    }
}
