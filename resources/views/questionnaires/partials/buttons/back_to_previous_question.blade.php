<a class="{{ $question->previousQuestion() === null ? 'btn btn-secondary text-center disabled' : 'btn btn-secondary text-center' }}" href="{{ route('questionnaires.show_previous_question', [ 'survey_questionnaire_id' => $survey->questionnaire_id]) }}" > <i class="fa-solid fa-circle-left"></i> Back to the previous question </a>
