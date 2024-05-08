@props(['base_route'])

@include('components.buttons.back_to_main',
    [
        'route'=> route($base_route),
        'button_id'=> $base_route.'_'.Auth::User()->id,
        'title'=>"Back To Surveys",
        ])

