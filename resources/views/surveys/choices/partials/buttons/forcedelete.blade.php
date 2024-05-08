@props(['base_route'])


@include('components.buttons.force_delete_modal',
    [
        'route' => route($base_route, ['choice' => $choice]),
        'button_id'=> $base_route.'_' . $choice->id,
        'data_bs_target' => 'delete-confirm-modal_'.$choice->id,
        'text' => 'Yes, permanently delete this choice',
        'target_type' => 'choice',
        'to_be_deleted_text'=>$choice->choice_text,
        ])

