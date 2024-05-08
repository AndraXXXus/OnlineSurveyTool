<form id={{'questionnaires.destroy_' . $survey->id}} method="POST" action="{{ route('questionnaires.destroy', $survey->id) }}">
    @method('DELETE')
    @csrf
    <a
        title="Archive"
        class="btn btn-danger inline-flex items-center justify-center"
        href="{{ route('questionnaires.destroy', $survey->id) }}"
        onclick="event.preventDefault(); document.getElementById('{{'questionnaires.destroy_' . $survey->id}}').submit();"
    >
        <i class="fa-solid fa-trash"></i>
    </a>
</form>
