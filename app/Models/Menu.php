<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function links()
    {
        return $this->hasMany(Link::class, 'menu_id')
            ->orderBy('order');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'menu_id')
            ->orderBy('order');
    }

    protected function entries(): Attribute
    {
        return Attribute::get(fn () => $this->links->merge($this->groups)
            ->filter(fn ($entry) => empty($entry->group_id))
            ->sortBy('order')
            ->values()
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }
}
