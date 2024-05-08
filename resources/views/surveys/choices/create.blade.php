@extends('layouts.app')
@section('title', isset($choice) ? "Edit choice" : "Add choice")

@section('content')
<div class="container">
    <div class="col d-flex justify-content-between p-3">
        @isset($choice)
            <p class="mb-2">You can edit this Choice here.</p>
        @else
            <p class="mb-2">You can add a new Choice here.</p>
        @endisset
        @include('surveys.choices.partials.buttons.to_choices',['base_route'=>'survey.question.show'])
    </div>
    @isset($choice)
    <form action="{{ route('choices.update', ['choice' => $choice]) }}" method="POST" enctype="multipart/form-data">
        @method('put')
    @else
    <form action="{{ route('choices.store', ['question' => $question]) }}" method="POST" enctype="multipart/form-data">
    @endisset
        @csrf

        <div class="form-group row mb-3">
            @isset($choice)
                @php
                    $question=$choice->question
                @endphp
            @endisset

            <label for="choice_text" class="col-sm-2 col-form-label"> {{ ($question->question_type === "open")  ? "Placeholder text*" : "Choice text*"}} </label>

            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control @error('choice_text') is-invalid @enderror"
                    id="choice_text"
                    name="choice_text"
                    value="{{ old('choice_text', isset($choice) ? $choice->choice_text : '') }}">

                @error('choice_text')
                <span class="text-danger d-block" role="alert">
                    {{ $message }}
                </span>
                @enderror
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{isset($question) ? $buttonLabel = "Update question" : "Add new question"}} </button>
        </div>

    </form>
</div>
@endsection

{{--
<script>
    const coverImageInput = document.querySelector('input#cover_image');
    const coverPreviewContainer = document.querySelector('#cover_preview');
    const coverPreviewImage = document.querySelector('img#cover_preview_image');

    coverImageInput.onchange = event => {
        const [file] = coverImageInput.files;
        if (file) {
            coverPreviewContainer.classList.remove('d-none');
            coverPreviewImage.src = URL.createObjectURL(file);
        } else {
            coverPreviewContainer.classList.add('d-none');
        }
    }
</script>
--}}


{{-- <script type="text/javascript">
    @error ('choice_text')
        $('#YOUR_MODAL_ID').modal('show');
    @enderror
</script> --}}



