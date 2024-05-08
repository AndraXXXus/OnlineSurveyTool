<div class="card mb-3" >
    <div class="row g-0">

        <div class="col-md-4 ">

            <img
                src="{{ isset($survey->cover_image_path) ? asset('storage/'.$survey->cover_image_path . '?' . rand() ) : asset('images/logo/logo.png') }}"
                alt="Survey cover"
                class="img-fluid rounded-start"
                style="display: block;
                width: auto;
                max-height: 390px;"
            />

        </div>

        <div class="col-md-8">
            <div class="card-header ">
                <h5 class="card-title">{{ $survey->survey_title }}</h5>
            </div>
            <div class="card-body ">

            <p class="card-text">{{ isset($survey->survey_description) ? $survey->survey_description : "No description" }}</p>

            </div>
            <div class="card-footer ">
                <p class="card-text"><small class="text-muted"> <i class="far fa-calendar-alt"></i> {{ $survey->updated_at }}</small></p>
            </div>

        </div>
    </div>
</div>
