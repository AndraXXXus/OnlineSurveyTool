@props(['base_route'])

@include('components.buttons.up_one_level',
    [
        'route'=> route($base_route),
        'button_id'=> $base_route.'_'.Auth::User()->id,
        'title'=>"Back To Surveys",
        ])
