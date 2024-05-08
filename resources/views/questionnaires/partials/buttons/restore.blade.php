<form id={{'questionnaires.restore_'.$survey->id}} method="POST" action="{{ route('questionnaires.restore', $survey->id) }}">
    @csrf
    <a
        title="Restore"
        class="btn btn-primary inline-flex items-center justify-center text-center text-white"
        href="{{ route('questionnaires.restore', $survey->id) }}"
        onclick="event.preventDefault(); document.getElementById('{{'questionnaires.restore_'.$survey->id}}').submit();"
    >
    <i class="fa-solid fa-redo-alt"></i>

    </a>

</form>
