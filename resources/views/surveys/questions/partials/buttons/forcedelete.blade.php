@props(['base_route'])


@include('components.buttons.force_delete_modal',
    [
        'route' => route($base_route, ['question' => $question]),
        'button_id'=> $base_route.'_' . $question->id,
        'data_bs_target' => 'delete-confirm-modal_'.$question->id,
        'text' => 'Yes, permanently delete this question',
        'target_type' => 'question',
        'to_be_deleted_text'=>$question->question_text,
        ])

