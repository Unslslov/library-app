<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year_published' => 'required|date',
            'available_copies' => 'required|integer|min:0',
            'total_copies' => 'required|integer|min:0|gte:available_copies',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|url',
        ];
    }
}
