<form id={{'questionnaire.getreplica_'.$survey->id}} method="POST" action="{{ route('questionnaire.getreplica', $survey->id) }}">
    @csrf
    <a
        title="Take a Copy"
        class="btn btn-info inline-flex items-center justify-center text-center"
        href="{{ route('questionnaire.getreplica', $survey->id) }}"
        onclick="event.preventDefault(); document.getElementById('{{'questionnaire.getreplica_'.$survey->id}}').submit();"
    >
    <i class="fa-regular fa-clone"></i>
    </a>

</form>
