{{-- <form id={{'questionnaires.forcedelete_' . $survey->id}} method="POST" action="{{ route('questionnaires.forcedelete', $survey->id) }}">
    @method('DELETE')
    @csrf
    <a
        title="Permanently delete"
        class="btn btn-danger inline-flex items-center justify-center"
        href="{{ route('questionnaires.forcedelete', $survey->id) }}"
        onclick="event.preventDefault(); document.getElementById('{{'questionnaires.forcedelete_' . $survey->id}}').submit();"
    >
        <i class="fa-solid fa-trash"></i>
    </a>
</form> --}}


@include('components.buttons.force_delete_modal',
    [
    'route' => route('questionnaires.forcedelete', $survey->id),
    'button_id' => 'archived_questionnaire_forcedelete_' . $team->id,
    'data_bs_target' => 'archived-questionnaire-forcedelete-modal_' . $team->id,
    'text' => 'Yes, permanently delete this questionnaire',
    'target_type' => 'questionnaire',
    'to_be_deleted_text' => $survey->survey_title,
    'title' => 'Permanently delete',
    ])


