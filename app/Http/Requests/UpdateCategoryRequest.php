<?php

namespace App\Http\Requests;

use App\Models\Categories;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'parent_id' => [
                'nullable', 'exists:categories,id',
                function (string $attribute, $value, \Closure $fail) {
                    $id = $this->get('id');
                    $category = Categories::where('id', $id)->first();

                    $children = Categories::getAllChildrenByParent($category);
                    $ids = array_map(fn($c) => $c->id, $children);

                    if (in_array($value, $ids)) {
                        return $fail('You cannot choose category as parent which is already a child of the category.');
                    }
                }
            ],
            'active' => ['required', 'boolean']
        ];
    }
}
