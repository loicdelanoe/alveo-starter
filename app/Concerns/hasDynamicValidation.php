<?php

namespace App\Concerns;

use App\Models\BlockType;
use App\Models\CollectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait hasDynamicValidation
{
    public function validateBlock(array $data, BlockType|CollectionType $type)
    {
        [$rules, $attributes] = $this->generateRules($type);

        $validator = Validator::make($data, $rules, [], $attributes);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }


public function validateAllBlocks(array $data, array $types): array
{
    $rules = [];
    $attributes = [];

    foreach ($data['blocks'] as $i => $block) {
        $type = $types[$i]; // Tu dois t'assurer que tu passes les BlockType dans le bon ordre
        foreach ($type->fields as $field) {
            $fieldPath = "blocks.$i.content.{$field['name']}";
            $rules[$fieldPath] = implode('|', $field['validation'] ?? []);
            $attributes[$fieldPath] = $field['name'];
        }
    }

    $validator = Validator::make($data, $rules, [], $attributes);

    if ($validator->fails()) {
        throw new ValidationException($validator);
    }

    return $validator->validated();
}

    public function generateRules(BlockType|CollectionType $type): array
    {
        $rules = [];
        $attributes = [];

        foreach ($type['fields'] as $field) {
            $fieldName = 'content.'.$field['name'];

            $fieldRules = $field['validation'] ?? [];

            $fieldRules = implode('|', $fieldRules);

            // Rename content.fieldName to fieldName
            $attributes[$fieldName] = $field['name'];

            // Push the rules to the rules array
            $rules[$fieldName] = $fieldRules;
        }

        return [$rules, $attributes];
    }
}
