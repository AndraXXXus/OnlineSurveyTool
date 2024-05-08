@props(['base_route'])

@include('components.buttons.edit',
    [
        'route' => route($base_route, ['survey' => $survey]),
        'button_id'=> $base_route.'_'.$survey->id
        ])

