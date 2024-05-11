@extends('layouts.app')
@section('title', isset($question) ? "Edit question" : "Add question")

@section('content')
<div class="container">
    <div class="col d-flex justify-content-between p-3">
        @isset($question)
            <p class="mb-2">You can edit this Question here.</p>
        @else
            <p class="mb-2">You can add a new Question here.</p>
        @endif
        @include('surveys.questions.partials.buttons.to_questions',['base_route'=>'questions.show'])
    </div>

    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 10000)" x-show="show">

        @if (Session::has('youtube_api_error'))
            <div class="alert alert-danger">
                Youtube Api Error1: {{Session::get('youtube_api_error')}}
            </div>
        @endif
    </div>

    @isset($question)
    <form action="{{ route('questions.update', ['question' => $question]) }}" method="POST" enctype="multipart/form-data">
        @method('put')
    @else
    <form action="{{ route('questions.store', ['survey' => $survey]) }}" method="POST" enctype="multipart/form-data">
    @endif
        @csrf
        <div class="form-group row mb-3">
            <label for="question_text" class="col-sm-2 col-form-label">Question text*</label>
            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control @error('question_text') is-invalid @enderror"
                    id="question_text"
                    name="question_text"
                    value="{{ old('question_text', isset($question) ? $question->question_text : '') }}">
                @error('question_text')
                <span class="text-danger d-block" role="alert">
                    {{ $message }}
                </span>
                @enderror
                <div class="col-sm-10 p-2">
                        <label class="text-xl text-gray-600">
                            <input type="checkbox"
                                name="question_required"
                                value="1"
                                @checked(old() ? old('question_required') : (
                                isset($question) ? $question->question_required : false) ) />

                                <span style="color: red" class="form-text">{{ __('Question mandatory?') }}</span>
                        </label>


                </div>

            </div>

        </div>


        <div class="form-group row mb-3 p-2">
            <label for="question_type" class="col-sm-2 col-form-label">Question Type*</label>
            <div class="col-sm-10">
                <select name="question_type" id="question_type" class="form-select @error('question_type') is-invalid @enderror" >
                    @isset($question)
                        @foreach ($allowedQuestionTypes as $allowedQuestionType)
                            <option class="form-group-option" value="{{$allowedQuestionType}}" {{$allowedQuestionType === old('question_type', $question->question_type) ? 'selected' : '' }}>
                                {{$allowedQuestionType}}
                            </option>
                        @endforeach
                    @else

                        <option class="form-group-option" value="" selected disabled >Open this to select a Question Type</option>

                        @foreach ($allowedQuestionTypes as $allowedQuestionType)
                        <option class="form-group-option" value="{{ $allowedQuestionType }}"
                        @if ($allowedQuestionType == old('question_type'))
                            selected="selected"
                        @endif
                        >{{ $allowedQuestionType }}</option>
                        @endforeach
                    @endisset

                </select>
                @error('question_type')
                    <span class="text-danger d-block" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>


        </div>

        <div x-data="{toggle: @js(old('imageOrVideoSwitch', isset($question) ? ($question->prefer_video == 1 ? true : false) : false))}" >
            {{-- x-cloak --}}
            <div class="d-flex justify-content-center items-center">
                <span class="mx-1"> Image </span>

                <div label="imageVideoSwitch" class="form-switch pl-10 mx-2">

                    <input name="imageOrVideoSwitch" title="imageVideoSwitch" x-model="toggle" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
                    :checked="toggle" >

                </div>

                <span >Video</span>
            </div>

            <div id="toggleOff" x-show="!toggle">
                @if(isset($question) && isset($question->cover_image_path))
                    @include('components.image_form.edit',['cover_image_path'=>$question->cover_image_path])
                @else
                    @include('components.image_form.create')
                @endisset
            </div>

            <div id="toggleOn" x-show="toggle">
                    @include('surveys.questions.partials.video_form.create')
            </div>

        </div>



        <div class="text-center p-4">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{isset($question) ? $buttonLabel = "Update question" : "Add new question"}} </button>

        </div>

    </form>
</div>
@endsection



<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const switch_button = document.getElementById('flexSwitchCheckDefault');
        const regex_error_messege = document.querySelector('#youtube_id_error_messege');

        switch_button.addEventListener("click", function() {
            videoInput.value= "";
            regex_error_messege.hidden = videoInput.value==="" || videoInput.value===@json(isset($question) ? $question->youtube_id : false) || false  ;
            clearInputFile(coverImageInput);
            if(videoInput.value!=""){
                videoPreviewContainer.classList.remove('d-none');
            }
            try {
                const youtube_id_error = document.getElementById('youtube_id_error');
                youtube_id_error.classList.add('d-none');
                videoInput.classList.remove('is-invalid');

            } catch (error) {;}
            try {
                const cover_image_error = document.getElementById('cover_image_error');
                cover_image_error.classList.add('d-none');
                coverImageInput.classList.remove('is-invalid');
            } catch (error) {;}

            try {
                const cover_image_error = document.getElementById('cover_image_error');
                cover_image_error.classList.add('d-none');
                coverImageInput.classList.remove('is-invalid');
            } catch (error) {;}

            try {
                const remove_cover_image = document.getElementById('remove_cover_image');
                remove_cover_image.checked=false;
            } catch (error) {;}


        });
    });

</script>

