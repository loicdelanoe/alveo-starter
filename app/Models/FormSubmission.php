<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
