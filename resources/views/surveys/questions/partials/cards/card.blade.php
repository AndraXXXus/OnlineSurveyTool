

@if(isset($survey->cover_image_path) && ($survey->deleted_at===null))
    @include('surveys.questions.partials.cards.image')
@else
    <div class="card text-center mb-3">
        <div class="card-header text-muted ">
            <h5 class="card-title mb-0">{{ $survey->survey_title }}</h5>
        </div>



        <div class="card-body">


            <p class="small mb-0">
                <span class="me-2">
                    <p class="card-text">
                        {{isset($survey->survey_description) ? $survey->survey_description : "No survey description available"}}
                    </p>
                </span>
            </p>
        </div>
        <div class="card-footer">
            <p class="text-center">
                <small class="text-muted"> <i class="far fa-calendar-alt"></i> {{ $survey->updated_at }} </small>
            </p>
        </div>
    </div>

@endisset
