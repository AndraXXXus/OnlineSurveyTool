@props(['base_route'])

@include('components.buttons.edit',
    [
        'route' => route($base_route, ['choice' => $choice]),
        'button_id'=> $base_route.'_'.$choice->id
        ])
