<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateChoiceRequest extends FormRequest
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

    public function rules()
    {
        return [
            'choice_text' => ['required', 'max:255'],
        ];
    }

    public function messages() {
        return [
            'choice_text.required' => 'Choice Text is required',
            'choice_text.max' => 'Tool long Choice Text, :max is the maximum!',
        ];
    }
}
