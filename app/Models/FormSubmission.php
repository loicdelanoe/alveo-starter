<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class FormSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'form_id',
        'data',
        'submitted_at',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    protected function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
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
