@props(['base_route'])

@include('components.buttons.force_delete_modal',
    [
        'route' => route($base_route, ['survey' => $survey]),
        'button_id'=> $base_route.'_' . $survey->id,
        'data_bs_target' => 'delete-confirm-modal_'.$survey->id,
        'text' => 'Yes, permanently delete this survey',
        'target_type' => 'survey',
        'to_be_deleted_text'=>$survey->survey_title,
        ])
