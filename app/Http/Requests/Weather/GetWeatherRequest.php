<?php

namespace App\Http\Requests\Weather;

use Illuminate\Foundation\Http\FormRequest;

class GetWeatherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'location' => 'sometimes|string|max:255',
            'days' => 'sometimes|integer|min:1|max:10',
        ];
    }
    
}
