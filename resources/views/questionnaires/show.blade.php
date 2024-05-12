@extends('layouts.guest')
@section('title', 'Fill out the Survey')

@section('content')

<div class="container">
    <div class="d-flex justify-content-center gap-3 ">
        <div class="col p-3 justify-content-center gap-3 text-center">
            <div class="col-12  mb-3 d-flex align-self-stretch p-3">
                <div class="col p-3 gap-3">
                    <form action="{{ route('questionnaires.store', ['survey_questionnaire_id' => $survey->questionnaire_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card w-100">

                            {{-- <div class="card-header">
                                <div class="d-flex items-center gap-2 justify-content-center ">
                                    @include('surveys.questions.partials.cards.card')
                                </div>
                            </div> --}}

                            <div class="card-body">
                                <div class="d-flex items-center gap-2 justify-content-center ">
                                    @include('questionnaires.partials.cards.questioncards')
                                </div>
                            </div>


                            <div class="card-footer">
                                <div class="d-flex items-center gap-2 justify-content-between ">
                                    @include('questionnaires.partials.buttons.back_to_previous_question')

                                    <button
                                        id="questionnaire_form_submit_button"
                                        type="submit"
                                        class="btn btn-primary text-xl"
                                        @disabled(false)>
                                        <i class="fa-solid fa-circle-right"></i>    {{(isset($last) && $last===true) ? $buttonLabel = "Finish Questionnaire" : "Next Qustion"}}</button>
                                </div>
                            </div>
                        </div>
                    </form>


                </div>
            </div>

        </div>

    </div>




</div>

@endsection

<script>

    document.addEventListener("DOMContentLoaded", function(event) {
        const submit_button = document.getElementById('questionnaire_form_submit_button');
        submit_button.disabled = @js(count($previous_answers_choice_ids)===0 ? $question->question_required==true && count($question->choices)>0 : false);
        submit_button.setAttribute('data-video-seen',!submit_button.disabled || @js($question->youtube_id===null));
        submit_button.setAttribute('data-inputs-valid',!submit_button.disabled);
    });
</script>
