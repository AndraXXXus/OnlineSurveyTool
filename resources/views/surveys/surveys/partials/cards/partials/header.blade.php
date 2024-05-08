@if(isset($archive))
    <h5 class="card-title mb-0" style="text-align: center">{{ Str::limit($survey->survey_title, 30) }}</h5>
@else
    <div class="d-flex items-center gap-2 justify-content-end">
        @include('surveys.surveys.partials.buttons.edit',['base_route'=>'surveys.edit'])
        @include('surveys.surveys.partials.buttons.clone',['base_route'=>'surveys.clone'])
        @include('surveys.surveys.partials.buttons.archive',['base_route'=>'surveys.destroy'])
    </div>

@endif
