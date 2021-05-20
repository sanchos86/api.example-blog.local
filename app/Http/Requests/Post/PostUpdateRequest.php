<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:6'],
            'text' => ['required', 'string'],
            'slug' => ['required', 'string', 'min:3', Rule::unique('posts')->ignore($this->post)],
            'publish' => ['required', 'boolean'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags' => ['array', 'exists:tags,id'],
            'picture' => ['sometimes', 'required', 'file'],
        ];
    }
}
