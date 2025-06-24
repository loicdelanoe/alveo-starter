<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_description',
        'favicon',
    ];

    public static function singleton(): Setting
    {
        return static::firstOrCreate([], [
            'site_name' => config('app.name', 'Alveo'),
            'site_description' => null,
            'favicon' => '/favicon.ico',
        ]);
    }
}
