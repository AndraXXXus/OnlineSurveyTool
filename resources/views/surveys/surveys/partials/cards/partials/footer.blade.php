<p class="small mb-0">
    <p class="text-center">
        <small class="text-muted"> <i class="far fa-calendar-alt"></i> {{ $survey->updated_at }}</small>
    </p>
    <p class="text-center">
        <small class="text-muted">
        {{$survey->team->team_name}}</small>
    </p>
</p>
<hr>
@if(isset($archive))
    <div class="d-flex flex-row justify-content-between">
        @include('surveys.surveys.partials.buttons.restore',['base_route'=>'surveys.restore'])
        @include('surveys.surveys.partials.buttons.force_delete_modal',['base_route'=>'surveys.forcedelete'])
    </div>
@else
    <div class="d-flex flex-row justify-content-between">
        @include('surveys.surveys.partials.buttons.details',['base_route'=>'questions.show'])
        @include('surveys.surveys.partials.buttons.golive',['base_route'=>'surveys.golive'])

    </div>
@endif
