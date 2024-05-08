@props(['base_route'])

@include('components.buttons.create',
    [
        'route'=> route($base_route),
        'button_id'=> $base_route.'_'.Auth::User()->id,
        ])

