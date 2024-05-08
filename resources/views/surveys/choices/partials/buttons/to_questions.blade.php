@props(['base_route'])

@include('components.buttons.up_one_level',
    [
        'route'=> route($base_route,  ['survey' => $survey]),
        'button_id'=> $base_route.'_'.$survey->id,
        'title'=>"Back To Surveys",
        ])
