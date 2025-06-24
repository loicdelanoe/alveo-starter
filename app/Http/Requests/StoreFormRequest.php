<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:forms,slug|max:255',
            'success_message' => 'nullable|string|max:255',
            'fields' => 'required|array',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.validation' => 'nullable|array',
            'fields.*.type' => 'required|string|in:text,textarea,email,number,select,checkbox,radio,date',
            // 'fields.*.options' => 'nullable|array',
            // 'fields.*.options.*' => 'string|max:255',
        ];
    }
}
