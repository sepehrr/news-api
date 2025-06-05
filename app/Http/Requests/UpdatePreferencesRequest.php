<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // User must be authenticated (handled by middleware)
    }

    public function rules(): array
    {
        return [
            'categories' => ['array'],
            'categories.*' => ['integer', Rule::exists('categories', 'id')],
            'authors' => ['array'],
            'authors.*' => ['integer', Rule::exists('authors', 'id')],
            'sources' => ['array'],
            'sources.*' => ['integer', Rule::exists('sources', 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'categories.*.exists' => 'One or more selected categories do not exist.',
            'authors.*.exists' => 'One or more selected authors do not exist.',
            'sources.*.exists' => 'One or more selected sources do not exist.',
        ];
    }
}
