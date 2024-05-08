@props(['base_route'])

@include('components.buttons.back_to_main',
    [
        'route'=> route($base_route,  ['survey' => $question->survey,'question' => $question]),
        'button_id'=> $base_route.'_'.$question->id,
        'title'=>"Back To Choices",
        ])
