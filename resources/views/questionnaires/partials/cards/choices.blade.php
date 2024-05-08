@if($question->question_type === "radio-button")
    @include('questionnaires.partials.questiontypes.radio-button')
@elseif ($question->question_type === "open")
    @include('questionnaires.partials.questiontypes.open')
@elseif ($question->question_type === "mutiple-choice")
    @include('questionnaires.partials.questiontypes.mutiple-choice')
@elseif ($question->question_type === "dropp-down")
    @include('questionnaires.partials.questiontypes.dropp-down')
@endif
