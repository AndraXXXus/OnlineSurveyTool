
<div class="card w-100">
    <div class="card-header">
        <h5 class="card-title"> {{$question->question_text}} {{ $question->question_required == true ? "*" :""}} </h5>
    </div>
    <div class="card-body">

        @include('questionnaires.partials.cards.choices')
    </div>
    <div class="card-footer">
        <p class="card-text">
            <small class="text-muted"> {{ "#" . $question->question_position ."/" . $survey->questions()->count() }}</small>
        </p>
    </div>
</div>
