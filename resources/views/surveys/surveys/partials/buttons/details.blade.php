@props(['base_route'])

@include('components.buttons.details',
    [
        'route'=> route($base_route,['survey' => $survey]),
        'button_id'=> $base_route.'_'.$survey->id,
        'text' => "Details",
        'title'=> "Details",
        ])

