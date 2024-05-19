@if(isset($archive))
<p class="text-center">
    {{$survey->id}}
</p>

@else
<h5 class="card-title mb-0" style="text-align: center">{{ Str::limit($survey->survey_title, 30) }}</h5>
@endif
<hr>
<p class="me-1" >
    Survey Description : {{ isset($survey->survey_description) ? Str::limit($survey->survey_description, 30) : "No description" }}
</p>
<p class="me-1" >
    Team info: {{ isset($survey->team_message) ? Str::limit($survey->team_message, 30) : "No description" }}
</p>
