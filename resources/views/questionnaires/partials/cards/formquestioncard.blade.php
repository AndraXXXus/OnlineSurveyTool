<div class="col p-3 gap-3">
    <form action="{{ route('questionnaires.store', ['survey_questionnaire_id' => $survey->questionnaire_id, 'question' => $question]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('questionnaires.partials.cards.questioncards')


    </form>
</div>
