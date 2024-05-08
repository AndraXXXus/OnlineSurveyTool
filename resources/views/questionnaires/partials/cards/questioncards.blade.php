

@if(isset($question->prefer_video) && (isset($question->youtube_id) || isset($question->cover_image_path)))
    @include('questionnaires.partials.cards.image_video')

@else
    @include('questionnaires.partials.cards.no_image_no_video')
@endisset






