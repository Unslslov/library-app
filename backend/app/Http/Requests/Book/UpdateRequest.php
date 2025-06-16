<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'genre' => 'sometimes|required|string|max:255',
            'publisher' => 'sometimes|required|string|max:255',
            'year_published' => 'sometimes|required|date',
            'available_copies' => 'sometimes|required|integer|min:0',
            'total_copies' => 'sometimes|required|integer|min:0|gte:available_copies',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|url',
        ];
    }
}
