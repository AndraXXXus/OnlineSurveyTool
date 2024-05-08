<form id={{'questionnaires.getreplica_'.$survey->id}} method="POST" action="{{ route('questionnaires.getreplica', $survey->id) }}">
    @csrf
    <a
        title="Take a Copy"
        class="btn btn-info inline-flex items-center justify-center text-center"
        href="{{ route('questionnaires.getreplica', $survey->id) }}"
        onclick="event.preventDefault(); document.getElementById('{{'questionnaires.getreplica_'.$survey->id}}').submit();"
    >
    <i class="fa-regular fa-clone"></i>
    </a>

</form>
