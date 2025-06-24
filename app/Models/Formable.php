<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formable extends Model
{
    /** @use HasFactory<\Database\Factories\FormableFactory> */
    use HasFactory;

    protected $fillable = [
        'form_id',
        'page_id',
    ];
}
