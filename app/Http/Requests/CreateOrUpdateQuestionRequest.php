<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Survey\Question;
use Illuminate\Validation\Rule;
use App\Rules\CheckYoutubeId;

class CreateOrUpdateQuestionRequest extends FormRequest
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
            'question_text' => ['required', 'string', 'max:255'],
            'question_required' => ['nullable', 'in:1'],
            'question_type' => ['required', Rule::in(Question::getAllowedQuestionTypes()),],
            'cover_image' => ['file', 'image', 'mimes:jpg,png', 'max:4096','prohibits:youtube_id',],
            'remove_cover_image' => ['nullable', 'boolean'],
            'youtube_id' => ['nullable', 'string', 'prohibits:cover_image', 'regex:/^([a-zA-Z0-9_-]{11})$/', new CheckYoutubeId(),],
            'imageOrVideoSwitch' => ['nullable', 'in:on'],
        ];
    }

    public function messages() {

        return [
            'question_text.required' => 'Question text is required',
            'question_text.max' => 'Tool long question text, :max is the maximum!',
            'question_type.required' => 'Selecting a question type is required',
            'question_type.in' => 'Question type must be one of the following: ' . implode(", ",Question::getAllowedQuestionTypes()),
            'cover_image.file' =>  'Image has to be a file!',
            'cover_image.image' =>  'File has to has to be an image!',
            'cover_image.max' =>  'Image can\'t be larger than :max bytes!',
            'youtube_id.regex' =>  ':attribute has to match the following youtube id pattern: ^([a-zA-Z0-9_-]{11})$ !',
            'imageOrVideoSwitch' => "Switch can be only: 'On' or null!"
               ];
    }
}
