@props(['base_route'])

@include('components.buttons.to_archives',
    [
        'route'=> route($base_route),
        'button_id'=> $base_route.'_'.Auth::User()->id,
        'title'=>"To Archived Questions",
        ])

