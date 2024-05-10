@props(['base_route'])

@include('components.buttons.to_archives',
    [
        'route'=> route("team.archived"),
        'button_id'=> "team.archived" . '_' . $user->id,
        'title'=>"To Archived TEams",
        ])
