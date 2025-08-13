<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|min:1|max:255',
            'author' => 'sometimes|required|string|min:1|max:255',
            'published_year' => 'sometimes|required|integer|between:1450,' . date('Y'),
            'summary' => 'nullable|string',
            'available' => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => is_string($this->title) ? trim($this->title) : $this->title,
            'author' => is_string($this->author) ? trim($this->author) : $this->author,
            'summary' => is_string($this->summary) ? trim($this->summary) : $this->summary,
            'available' => filter_var($this->available, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        ]);
    }
}
