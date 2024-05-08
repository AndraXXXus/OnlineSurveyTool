
<div class="card w-100 mb-3 ">
    <div class="card-header">
        @if($question->prefer_video)
            @isset($question->youtube_id)
                @include('questionnaires.partials.cards.video')
                <hr>
            @endisset
        @else
            @isset($question->cover_image_path)
                @include('questionnaires.partials.cards.image')
                <hr>
            @endisset
        @endisset
        <h5 class="card-title"> {{$question->question_text}} {{ $question->question_required == true ? "*" :""}} </h5>
    </div>
    <div class="card-body">
        @include('questionnaires.partials.cards.choices')
    </div>
    <div class="card-footer">

        <p class="card-text">
            <small class="text-muted">  {{ "#" . $question->question_position ."/" . $survey->questions()->count() }}</small>
        </p>
    </div>

</div>
