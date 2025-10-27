<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoResourceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:1024'],
            'canonical_url' => ['nullable', 'string', 'max:1024'],
            'robots' => ['nullable', 'string', 'max:255'],
            'schema' => ['nullable', 'string'],
        ];
    }
}
