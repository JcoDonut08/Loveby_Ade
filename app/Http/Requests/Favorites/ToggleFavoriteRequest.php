<?php

namespace App\Http\Requests\Favorites;

use App\Services\ProductCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(ProductCatalog $catalog): array
    {
        return [
            'slug' => ['required', 'string', Rule::in($catalog->all()->pluck('slug')->all())],
        ];
    }
}
