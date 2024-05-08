@props(['base_route'])

@include('components.buttons.back_to_main',
    [
        'route'=> route($base_route,  ['survey' => $survey]),
        'button_id'=> $base_route.'_'.$survey->id,
        'title'=>"Back To Questions",
        ])
