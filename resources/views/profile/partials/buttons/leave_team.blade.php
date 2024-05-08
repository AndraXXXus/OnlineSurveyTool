@include('components.buttons.force_delete_modal',
    [
        'route' => route('teamuser.leave_team', ['team'=>$team]),
        'button_id' => 'leave_team_'.$team->id,
        'data_bs_target' => 'leave-team-confirm-modal_'.$team->id,
        'text' => 'Yes, leave this team and lose all related surveys',
        'target_type' => 'team membership',
        'to_be_deleted_text'=>$team->team_name,
        'title' => 'Leave Team and lose all related surveys!',
        ])
