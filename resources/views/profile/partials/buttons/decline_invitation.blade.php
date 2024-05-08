@include('components.buttons.force_delete_modal',
[
    'route' =>route('teamuser.decline_invitation',['team'=>$team]),
    'button_id' => 'decline_pending_'.$team->id,
    'data_bs_target' => 'decline-invitation-modal_'.$team->id,
    'text' => 'Yes, decline this team inviation',
    'target_type' => 'inviation',
    'to_be_deleted_text'=>$team->team_name,
    'title' => 'Decline Inviation',
    ])
