@if(isset($archive))
<p class="text-center">
    {{$survey->id}}
</p>

@else
<h5 class="card-title mb-0" style="text-align: center">{{ Str::limit($survey->survey_title, 30) }}</h5>
@endif
<hr>
@if (isset($survey->cover_image_path )&& $survey->deleted_at===null)
    <div class="d-flex items-center gap-2 justify-content-center">
        @include('surveys.surveys.partials.cards.partials.img_card')
    </div>
@else
    <p class="me-1" >
        {{ isset($survey->survey_description) ? Str::limit($survey->survey_description, 30) : "No description" }}
    </p>
@endif
