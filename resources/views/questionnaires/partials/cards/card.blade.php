<div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch p-3">
    <div class="card w-100">
        <div class="card-header">
            @if(!isset($archive))
                <div class="d-flex items-center gap-2 justify-content-end">
                    @include('questionnaires.partials.buttons.getreplica')
                    @can('questionnaireOwnerAndUserMatchOrIsAdmin',$survey)
                    @include('questionnaires.partials.buttons.archive')
                    @endcan
                </div>
                @else
                <span>
                    {{$survey->id}}
                </span>
            @endif
        </div>

        <div class="card-body">

            <h5 class="card-title mb-0">{{ Str::limit($survey->survey_title, 30) }}</h5>
            <hr>
            <span class="me-2">
                <span>
                    @if(isset($archive))
                        {{$survey->id}}
                    @else
                        <p class="me-1" >
                            Description: {{Str::limit($survey->survey_description, 30) }}
                        </p>
                        <p class="me-1" >
                            Team info: {{ isset($survey->team_message) ? Str::limit($survey->team_message, 50) : "" }}
                        </p>
                    @endif
                </span>
            </span>

            <p class="small mb-0">

                <span>
                    <i class="far fa-calendar-alt"></i>
                    @if(isset($archive))
                    <span>{{ $survey->deleted_at }}</span>
                    @else
                    <span>{{ $survey->updated_at }}</span>
                    @endif
                </span>

            </p>
        </div>
        <div class="card-footer">
            <div class="d-flex flex-row justify-content-between">
                @if(isset($archive))
                    @include('questionnaires.partials.buttons.restore')
                    @can('questionnaireOwnerAndUserMatchOrIsAdmin',$survey)
                    @include('questionnaires.partials.buttons.forcedelete')
                    @endcan
                @else
                    @include('questionnaires.partials.buttons.fillout')
                    @include('questionnaires.partials.buttons.showStats')
                @endif
            </div>
        </div>
    </div>
</div>
