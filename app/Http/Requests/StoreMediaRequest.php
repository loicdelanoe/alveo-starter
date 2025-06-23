<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files' => 'required|array|max:10240',
            'files.*' => ['required', 'file', File::types(['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'mp4', 'mov', 'svg', 'pdf'])
                ->max(10240)],
        ];
    }

    public function attributes(): array
    {
        return [
            'files.*' => 'files',
        ];
    }
}
