<?php

namespace App\Http\Requests;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255', 'exists:menus,slug', new Slug],
            'active' => 'boolean',
            'navigations' => 'array',
            'navigations.*.menu_id' => 'nullable|integer|exists:menus,id',
            'navigations.*.parent_id' => 'nullable|integer',
            'navigations.*.navigable_id' => 'nullable|integer',
            'navigations.*.navigable_type' => 'nullable|string|in:page,link',
            'navigations.*.order' => 'integer|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'pages' => 'menu pages',
        ];
    }
}
