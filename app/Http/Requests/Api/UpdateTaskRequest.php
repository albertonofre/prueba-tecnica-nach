<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'El título no puede exceder 255 caracteres.',
            'completed.boolean' => 'El campo completado debe ser verdadero o falso.',
        ];
    }
}