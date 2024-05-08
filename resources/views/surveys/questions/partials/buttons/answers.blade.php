@props(['base_route'])

@include('components.buttons.details',
    [
        'route'=> route($base_route,['survey' => $survey,'question' => $question]),
        'button_id'=> $base_route.'_'.$question->id,
        'title'=>"Answers",
        'text' => ""
        ])

