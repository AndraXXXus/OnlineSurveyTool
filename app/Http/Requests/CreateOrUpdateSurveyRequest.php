<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateSurveyRequest extends FormRequest
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
            'team_id' => ['required', 'string', 'max:255', 'exists:teams,id'],
            'survey_title' => ['required', 'max:255'],
            'survey_description' => ['nullable', 'max:512'],
            'cover_image' => ['file', 'image', 'mimes:jpg,png', 'max:4096'],
            'remove_cover_image' => ['nullable', 'boolean'],
        ];
    }

    public function messages() {
        return [
            'survey_title.required' => 'Title is required',
            'survey_title.max' => 'Tool long title, :max is the maximum!',
            'survey_description.max' =>  'Tool long description, :max is the maximum!',
            'cover_image.file' =>  'Image has to be a file!',
            'cover_image.image' =>  'File has to has to be an image!',
            'cover_image.max' =>  'Image can\'t be larger than :max bytes!',
        ];
    }
}
